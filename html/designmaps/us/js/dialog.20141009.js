/**
 * Class inistantiation. Calls the Dialog internal init method. See
 * documentation for init for more details. This is essentially how Object
 * Oriented Programming works for Javascripts. An "object" is declared as a
 * "function", and then we can bind "properties" (or more commonly methods) to
 * this "object". Javascript is not a fully object-oriented language, but it
 * gets the job done in this case.
 *
 * The _options parameter passed to the three public methods of this class
 * allows the user to customize the look/feel of the dialog. The available
 * options are as follows:
 * _options = {
 *		title        : 'text displayed in title of dialog',
 *		ok_title     : 'text displayed on ok button',
 *		cancel_title : 'text displayed on cancel button',
 *		mask_id      : 'id value of masker'
 *		default_value: 'default text in input field'
 *      ctype        : 'text/html to use an HTML DOM object as the message.'
 *      width        : 'width (in px) of the dialog pop-up'
 *      height       : 'height (in px) of the dialog pop-up'
 * };
 *
 * Note: The 'cancel_title' is only used when the Cancel button is displayed,
 * and the 'default_value' is only used on prompt dialogs.
 *
 * -=* CHANGE LOG *=-
 * 05/08/08 -- EMM: Added the 'ctype' parameter to the _options. Allows users to
 *                  create dialogs with HTML markup as the message. Also allow
 *                  users to now specify the width/height of the overall wrapper
 *                  object. This helps when using HTML DOM objects as the
 *                  message so content does not overflow or get clipped.
 *
 * 05/21/08 -- EMM: Created a 'showLoading(_message, _options)' method to show
 *                  an indeterminate loading dialog. Useful for complex pages or
 *                  long downloads.
 *
 * 05/27/08 -- EMM: Added hack for IE6 that allowed window controls (select box
 *                  etc...) to display on top of the dialog despite z-indexing.
 *                  The fix involved using an IFRAME (yuck) as the mask.
 *
 * 11/25/08 -- EMM: Put the "focus" commands in a try catch to avoid errors in
 *                  IE when the focus element is hidden or otherwise not
 *                  displayed.
 *
 * @author  Eric Martinez
 * @date    05/06/2008
 * @version 0.0.1
 */
function Dialog(_name) {
	this.init(_name);
}

/**
 * We extend the Javascript idea of a "prototype" to include all the following
 * methods. See internal documentation for more details.
 */
Dialog.prototype = {
	/**
	 * This is the "constructor" of the Dialog object. With dialogs, (as with
	 * highlander), there can be only one. Whenever a new dialog is instantiated
	 * the previous one is removed and the new one is attached to the
	 * "window.IO" object. If a user wishes to include custom styles for their
	 * dialog, they can instantiate a new dialog with a custom _name and that
	 * _name will be used as the parent wrapper DOM element for the dialogs.
	 *
	 * @param _name - String - The name of this dialog box generator. The _name
	 *                         is used as the "id" value for the container DOM
	 *                         element of all dialogs created with this dialog
	 *                         object. If a user provides a custom name, they
	 *                         must also provide styles as the dialog will be
	 *                         otherwise unstyled.
	 *
	 * @return void
	 */
	init: function(_name) {
		this.name = _name || 'io_wrapper';
		this.callback = function() { };
		this.isIe = (window.navigator.appName == "Microsoft Internet Explorer");
		window.IO = this;
	},

	/**
	 * The most basic dialog box for the user. Conveys a simple notification to
	 * the user. If a _callback is specified, it is called when the user closes
	 * the dialog box. See the class documentation for more information about
	 * what options are available for customization.
	 *
	 * @param _message  - String   - The message to display to the user.
	 * @param _options  - Object   - The customizing options for this dialog.
	 * @param _callback - Function - The method to call when the dialog is
	 *                               closed. This method may receive a
	 *                               true/false parameter depending on how the
	 *                               dialog is closed. True for the enter key
	 *                               press or Ok click; false for the Escape
	 *                               keypress or "X" click.
	 *
	 * @return void
	 */
	alert: function(_message, _options, _callback) {
		_options = _options || new Object();
		_options.message = _message;
		_options.type    = 'message';
		if(typeof _callback == 'function') { this.callback = _callback; }
		else { this.callback = function() {}; }
		this.showMask(new Object());
		this.createDialog(_options);
	},

	/**
	 * A more useful dialog to interact with the user. This dialog method asks
	 * the user a "Yes"/"No" questions with two clickable buttons respectively
	 * labeled. The _callback method is executed when the dialog is closed. This
	 * method should expect a true/false value as the sole argument.
	 *
	 * @param _message  - String   - The message to show the user.
	 * @param _options  - Object   - The customizing options for this dialog.
	 * @param _callback - Function - Callback method executed on close. This
	 *                               method can expect a true/false argument
	 *                               depending on how the dialog was closed.
	 *                               True for Enter keypress or Ok click; false
	 *                               for Escape keypress, Cancel click, or "X"
	 *                               click.
	 *
	 * @return void
	 */
	confirm: function(_message, _options, _callback) {
		_options = _options || new Object();
		_options.message = _message;
		_options.type    = 'confirm';
		if(typeof _callback == 'function') { this.callback = _callback; }
		else { this.callback = function() {}; }
		this.showMask(new Object());
		this.createDialog(_options);
	},

	/**
	 * The most complicated of the dialog boxes. A dialog box is opened with the
	 * given _message displayed. This message should prompt the user for
	 * free-form input that can be accepted in a single text input field. The
	 * _callback is executed when the dialog is closed and it should expect the
	 * input value as an argument.
	 *
	 * @param _options  - Object   - The customizing options for this dialog.
	 * @param _callback - Fucntion - The method executed when the dialog is
	 *                               closed. Receives the input value as an
	 *                               argument if the user pressed the Enter key
	 *                               or clicked the okay button. Receives static
	 *                               false value if user pressed the Escape key,
	 *                               clicked Cancel, or clicked the "X". Note
	 *                               that input validation is not performed, and
	 *                               return value may be the empty string if
	 *                               user answered in the affirmative and did
	 *                               not enter a value.
	 * @return void
	 */
	prompt: function(_message, _options, _callback) {
		_options = _options || new Object();
		_options.message = _message;
		_options.type    = 'input';
		if(typeof _callback == 'function') { this.callback = _callback; }
		else { this.callback = function() {}; }
		this.showMask(new Object());
		this.createDialog(_options);
	},

	/**
	 * Shows an indeterminate progress bar with a loading gif and the given
	 * _message explaining why they are seeing the message (i.e. "Application
	 * Initializing..."). The _options can be used to customize the dialog.
	 *
	 * @param _message - String - Description of why the loading box is shown.
	 * @param _options - Object - Customizing options for this dialog.
	 * @return void
	 */
	showLoading: function(_message, _options) {
		_options = _options || new Object();
		_options.message = 'Loading...';
		_options.title = _message || 'Loading...';
		_options.type  = 'message';
		_options.width = '250';
		this.callback = function() { };

		// Show a simple dialog
		this.showMask(new Object());
		this.createDialog(_options);

		// Remove the user controls to close the dialog
		this.removeWindowHandlers();
		var close  = document.getElementById('io_close_dialog');
		var frm   = document.getElementById('io_form');
		close.parentNode.removeChild(close);
		frm.parentNode.removeChild(frm);

		// Create a custom loading gif...
		var loadingGif = document.createElement('img');
		loadingGif.alt = 'Loading...';
		loadingGif.src = '/images/loader.gif';


		// Attach the image to the dialog
		var d = document.getElementById(window.IO.name);
		addClass(d, 'loading');
		d.appendChild(loadingGif);
	},

	/** Private Methods. Users need not look further. **/

	/**
	 * Creates a "mask" object to cover all the page except for the dialog
	 * itself. This mask is by default a semi-transparent black DIV element.
	 * This mask also provides for blocking to prevent the user from interacting
	 * with the page until the dialog is closed.
	 *
	 * @param _options - Object - Currently no options are read for this method.
	 * @return void
	 */
	showMask: function(_options) {
		var m = null;
		// IE hack for windowed elements in IE6. (CHLG: 3) *sigh*
		if(navigator.appVersion.indexOf('MSIE 6.0') != -1) {
			m = document.createElement('iframe'); 
		} else {
			m = document.createElement('div');
		}
		var d = document.documentElement;
		var b = document.getElementsByTagName('body')[0];
		var sX = document.body.scrollLeft||self.pageXOffset||(d&&d.scrollLeft);
		var sY = document.body.scrollTop||self.pageYOffset||(d&&d.scrollTop);
		var pX = document.body.clientWidth||self.innerWidth||(d&&d.clientWidth);
		var pY = document.body.clientHeight || self.innerHeight || 
				(d&&d.clientHeight);
		if(this.isIe) { pY += 32; pX -= 1;} // Hack for IE screen sizes
		m.src = '/template/widgets/dialog/blank.html'; // Hack for IE (CHLG: 3)
		m.style.width = pX + sX + 12 + 'px';
		m.style.height = pY + sY + 'px';

		m.id  = (_options.mask_id)?_options.mask_id:'masker';
		b.appendChild(m);
	},

	/**
	 * Removes the mask that is created with the "showMask" method. Users can
	 * once again see the screen un-hindered, and can interact with the page.
	 *
	 * @param _options - Object - Currently no options are read for this method.
	 *                            However this object should sill be present as
	 *                            a "new Object()".
	 * @return void
	 */
	hideMask: function(_options) {
		var mid  = (_options.mask_id)?_options.mask_id:'masker';
		var m = document.getElementById(mid);
		m.parentNode.removeChild(m);
	},

	/**
	 * Closes the dialog that is displayed from one of the custom public methods
	 * of this object. The dialog is closed, then the callback method (if
	 * specified) is called with the parameter as described in the public method
	 * that opened the dialog. Finally after the callback returns, the mask is
	 * removed from the display and all dialog event handlers are removed from
	 * the window.
	 *
	 * @param _event - Event - The window event that triggered this method call.
	 * @return false - The output from the preventDefault(_event) call.
	 */
	closeDialog: function(_event) {
		try{/* A dummy Try/Catch for IE's benefit.*/
		var that = window.IO;
		var d = document.getElementById(that.name);
		var v = that.parseDesiredReturn(_event);
		d.parentNode.removeChild(d);
		that.callback.apply(that, new Array(v));
		that.hideMask(new Object());
		that.removeWindowHandlers();
		return preventDefault(_event);
		}catch(e){ /* Ignore. Might happen if this is not event driven. */ }
	},

	/**
	 * Creates a custom dialog depending on the _options given. The _options
	 * should specify the "type" of dialog to show; this type can be one of
	 *
	 * message - A simple alert dialog.
	 * confirm - A boolean yes/no dialog.
	 * input   - A complicated input field dialog.
	 * 
	 * The _options may also specify the "title" to use on the dialog. That is,
	 * the text to display as the name of the dialog "window". Dialogs are
	 * displayed in the center of the screen with a "mask" the blocks all other
	 * interaction with the page until the dialog is closed.
	 *
	 * @param _options - Object - An object as specified above.
	 * @return void
	 */
	createDialog: function(_options) {
		// Create the high-level DOM elements.
		var w = document.createElement('div');
		var h = document.createElement('h3');
		var f = document.createElement('form');
		f.id  = 'io_form';

		w.id = this.name;

		// Allow user to specify dimensions of prompt. Must be done in pixels.
		if(_options.width) { w.style.width = _options.width + 'px'; }
		if(_options.height) {
			w.style.height = _options.height + 'px';
			w.style.overflow = 'auto';
		}

		// Finish up the header
		var lbl = _options.title || document.title;
		h.appendChild(document.createTextNode(lbl));
		var s = document.createElement('span');
		s.id  = 'io_close_dialog';
		s.appendChild(document.createTextNode('x'));
		s.addEventListener('click', this.closeDialog);
		h.appendChild(s);
		w.appendChild(h);

		// Finish up the form
		f.addEventListener('submit', this.closeDialog);
		var l  = document.createElement('label');

		f.appendChild(l);
		var p = '';
		var t = this.getMessageType(_options);
		if(t == 'message') {
			p = 'Something of note has happened on this page.';
			f.appendChild(this.createOkayButton(_options));
			var cn = this.createCancelButton(_options);
			if(this.isIe) { cn.style.width='0';cn.style.height='0'; } else
			{ cn.style.display = 'none'; }
			f.appendChild(cn);
		} else if (t == 'confirm') {
			p = 'Please confirm you want the selected action to continue.';
			f.appendChild(this.createOkayButton(_options));
			f.appendChild(this.createCancelButton(_options));
		} else if (t == 'input') {
			p = 'Please enter a value to use for the selected action.';
			f.appendChild(this.createInputField(_options));
			f.appendChild(this.createOkayButton(_options));
			f.appendChild(this.createCancelButton(_options));
		}

		if(_options.message) { p = _options.message; }

		// Allow user to alert HTML content if desired.
		if(_options.ctype&&_options.ctype=='text/html') {
			l.appendChild(p);
		} else {
			l.appendChild(document.createTextNode(p));
		}

		w.appendChild(f);
		var b = document.getElementsByTagName('body')[0];
		b.appendChild(w);
		this.centerElement(w);
		this.addWindowHandlers();
		w.style.visibility = 'visible';
		// Reset the focus element if possible
		var i = document.getElementById('io_input_val');
		if(i) { try{i.focus();}catch(e){/*Ignore*/} } else {
			i = document.getElementById('io_btn_ok');
			if(i) { try{i.focus();}catch(e){/*Ignore*/} }
		}
	},

	/**
	 * Centers the given _element in the viewport of the browswer window. This
	 * is done fairly accurately, however is sometimes slightly off for older or
	 * obscure browsers.
	 *
	 * @param _element - DOM Element - The element to center.
	 * @return void
	 */
	centerElement: function(_element) {
		var d = document.documentElement;
		var sX = self.pageXOffset||(d&&d.scrollLeft)||document.body.scrollLeft;
		var sY = self.pageYOffset||(d&&d.scrollTop)||document.body.scrollTop;
		var pX = self.innerWidth||(d&&d.clientWidth)||document.body.clientWidth;
		var pY = self.innerHeight || (d&&d.clientHeight) ||
				document.body.clientHeight;
		var eX = _element.offsetWidth || _element.style.pixelWidth || 360;
		var eY = _element.offsetHeight || _element.style.pixelHeight || 83;
		var x = Math.floor(pX/2) - Math.floor(eX/2) + sX;
		var y = Math.floor(pY/2) - Math.floor(eY/2) + sY;
		_element.style.left = x + 'px';
		_element.style.top  = y + 'px';
	},

	/**
	 * Parses the given _options for a 'type' variable. If the "type" is present
	 * and is one of "message", "confirm", or "input", then the "type" is
	 * returned. Otherwise "message" is returned.
	 *
	 * @param _options - Object - A customizing object as specified above.
	 * @return A string specifying the desired dialog type.
	 */
	getMessageType: function(_options) {
		var type = 'message';
		if(_options.type && (
				_options.type == 'message' ||
				_options.type == 'confirm' ||
				_options.type == 'input'
			)) {
			type = _options.type;
		}
		return type;
	},

	/**
	 * Creates an Ok button DOM element to be displayed on the form.
	 *
	 * @param _options - Object - Customizing options allows user to specify the
	 *                            display text on the button. Allows for
	 *                            internationalization if desired. Use
	 *                            "ok_title" to specify the custom label.
	 *
	 * @return A DOM input element of type "submit".
	 */
	createOkayButton: function(_options) {
		var ok  = document.createElement('input');
		ok.name = 'io_btn_ok';
		ok.id   = 'io_btn_ok';
		ok.type = 'submit';
		var t   = (_options.ok_title)?_options.ok_title:'   Ok   ';
		ok.value = t;
		ok.addEventListener('click', this.closeDialog);
		return ok;
	},

	/**
	 * Creates a Cancel button DOM element to be displayed on the form.
	 *
	 * @param _options - Object - Customizing options allows user to specify the
	 *                            display text on the button. Allows for
	 *                            internationalization if desired. Use
	 *                            "cancel_title" to specify the custom label.
	 *
	 * @return A DOM input element of type "reset".
	 */
	createCancelButton: function(_options) {
		var cl  = document.createElement('input');
		cl.name = 'io_btn_cancel';
		cl.id   = 'io_btn_cancel';
		cl.type = 'reset';
		var t   = (_options.cancel_title)?_options.cancel_title:'Cancel';
		cl.value = t;
		cl.addEventListener('click', this.closeDialog);
		return cl;
	},

	/**
	 * Creates an input DOM element to be displayed on the form to accept
	 * textual input from the user.
	 *
	 * @param _options - Object - Customizing options allow user to specify a
	 *                            default value to display in the text field.
	 *                            This is optional and can be specified using
	 *                            the "default_value" parameter.
	 *
	 * @return A DOM input element of type "text".
	 */
	createInputField: function(_options) {
		var i   = document.createElement('input');
		i.name  = 'io_input_val';
		i.id    = 'io_input_val';
		i.type  = 'text';
		i.value = _options.default_value || '';
		return i;
	},

	/**
	 * Adds event handlers to the window.onkeydown, window.onresize, and
	 * window.onscroll events. These are used only while a dialog is visible on
	 * the screen. The latter two are only used in Gecko-based (Mozilla, Camino,
	 * Safari etc...) browsers because IE behaves very badly for the
	 * functionality we require. This detracts slightly from IE but not
	 * significatnly.
	 *
	 * @return void
	 */
	addWindowHandlers: function() {
		var that = window.IO;
		window.onkeydown = function(_event) {
			var c = _event.charCode || _event.keyCode;
			if (c==13) {
				var b = document.getElementById('io_btn_ok');
				try{b.click();}
				catch(e){window.IO.closeDialog();}
				preventDefault(_event);
				return false;
			} else if (c==27) {
				var b = document.getElementById('io_btn_cancel');
				try{b.click();}
				catch(e){window.IO.closeDialog();}
				preventDefault(_event);
				return false;
			}
		};
		window.onresize = function(_e) {
			that.centerElement(document.getElementById(that.name));
			if(that.isIe) { return; } // IE behaves badly trying to do this.
			that.hideMask(new Object());that.showMask(new Object());
		};
		window.onscroll = function(_e) {
			that.centerElement(document.getElementById(that.name));
			if(that.isIe) { return; } // IE behaves badly trying to do this.
			that.hideMask(new Object());that.showMask(new Object());
		};
	},

	/**
	 * Removes the window event handlers that are created in the
	 * "addWindowHandlers" method. This prevents these events from being
	 * triggered when the dialog is not visible and hence the handlers make no
	 * sense and would fail.
	 *
	 * @return void
	 */
	removeWindowHandlers: function() {
		window.onkeydown = function() { };
		window.onresize  = function() { };
		window.onscroll  = function() { };
	},

	/**
	 * Checks the triggering _event and determines what return value to provide
	 * to the callback method (specified when opening the dialog). Callback
	 * arguments are specified in the public methods and should be adhered to
	 * for any hope of expected behavior.
	 *
	 * @param _event - Event - The event that triggered this method call.
	 * @return The argumnent value to pass to the callback method.
	 */
	parseDesiredReturn: function(_event) {
		if(!_event) { return false; }
		var t = _event.currentTarget || _event.srcElement;
		var s = t.id;
		var r = true;
		if(s=='io_btn_ok'||s=='io_form') {
			var q = document.getElementById('io_input_val');
			if(q) {
				r = q.value;
			}
		} else {
			r = false;
		}

		return r;
	}
};
