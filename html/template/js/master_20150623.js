
// declare as global
var _gaq = _gaq || [];


// Share buttons: enhance buttons to include hash, open popup, track in Google Analytics
var initShareButtons = function() {
  $('#share a').bind('click', function() {
    var network = $(this).text();
    var uri_share = $(this).attr('href') + escape(window.location.hash); // add hash to URL if present
    var uri_page = window.location.href

    // Google Analytics Social Interaction Tracking
    if (typeof(_gaq) != "undefined") _gaq.push([
        '_trackSocial', 
        network, // network
        'share', // socialAction
        uri_page // opt_target
      ]);

    if (network == 'Email') { // use default action and update href
      $(this).attr('href', uri_share);
      return true;
    }

    var width = (screen.width >= 980) ? 980 : 640;
    var share_window = window.open (uri_share, 'share', 'width=' + width + ',height=500,scrollbars=1');
    return false;
  });
};


// Google Analytics: load
var initGoogleAnalytics = function() {
	_gaq.push(['_setAccount', 'UA-7320779-1']);
	_gaq.push(['_gat.anonymizeIp']);
	_gaq.push(['_trackPageview']);
	(function() {
		var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
		ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	})();
};


// Google Search: enhance each form to use javascript search results
var initGoogleSearch = function() {
	$('form.search-form').each(function() {
		var me = $(this);

		//prevent new window
		me.attr('target', '');
		//send to search results page
		me.attr('action', '/search/');

		//add key
		me.append('<input type="hidden" name="cx" value="012856435542074762574:49ga9ubtojk"/>');
		me.append('<input type="hidden" name="cof" value="FORID:11"/>');
		me.append('<input type="hidden" name="sa" value="Search"/>');

		//remove site: from search, cse implies site list
		$('.search-siteonly', me).remove();
	});
};


// call once page is ready
$(document).ready(function() {
	initGoogleAnalytics();
	//initGoogleSearch();
	initShareButtons();
});


// legacy functions from lib.js
// These functions should be considered deprecated
if(top != self){
	if (top.location.host.indexOf("usgs.gov") == -1) {
		// only frame bust when in a non-usgs frame
		top.location=self.location;
	}
}

function addEvent(a,e,d){var b="on"+e;if(a.addEventListener){a.addEventListener(e,d,false)}else if(a.attachEvent){a.attachEvent(b,d)}else{var i=a[b];a[b]=function(){var f=i.apply(this,arguments),g=d.apply(this,arguments);return f==undefined?g:(g==undefined?f:g&&f)}}};
function getClasses(e){var e=getElement(e),s=e.className;return s?s.split(' '):[]};
function addClass(e,c){var i=0,s=e.className;if(s&&s!=''){s=s.split(' ');for(;s[i];i++){if(s[i]==c)return}s.splice(0,0,c);s=s.join(' ')}else{s=c}e.className=s};
function removeClass(e,c){var i=0,s=e.className;if(s&&s!=''){s=s.split(' ');for(;s[i];i++){if(s[i]==c){s.splice(i,1);e.className=s.join(' ');break}}}};
function hasClass(e,c){var i=0,s=e.className;if(s&&s!=''){s=s.split(' ');for(;s[i];i++){if(s[i]==c){return true}}}return false};
var CRAWLBACKS = [];
function addCrawlback(f) { var d = document, l;if (d.all || d.getElementsByTagName) {l = CRAWLBACKS.length; if (l == 0) { addEvent(window, 'load', function() { crawlDOM(CRAWLBACKS); }); } CRAWLBACKS[l] = f; }};
function crawlDOM(cb) { var d = document, i, j, el, els = d.all || d.getElementsByTagName("*"); var dom = new Array(); for (i = 0; (el = els[i]); i++) { dom[dom.length] = el;} for (i = 0; (el = dom[i]); i++) {for (j = 0; j < cb.length; j++) {cb[j](el);}}};
function eventTarget(e){var e=getEvent(e),t=e.target;return t?(t.nodeType==3?t.parentNode:t):e.srcElement};
function eventPosition(e){var e=getEvent(e),w=window,ih=w.innerHeight,iw=w.innerWidth,x=e.pageX,y=e.pageY,xo=w.pageXOffset,yo=w.pageYOffset;if(!ih&&!iw){var d=document,b=d.getElementsByTagName('BODY')[0],d=d.documentElement;ih=d.clientHeight;iw=d.clientWidth;xo=b.scrollLeft+d.scrollLeft;yo=b.scrollTop+d.scrollTop;x=e.clientX+xo;y=e.clientY+yo}return{'x':x,'y':y,'t':yo,'r':xo+iw,'b':yo+ih,'l':xo}};
function getEvent(e){return e?e:window.event};
function getElement(e){if(typeof e=='string'){e=document.getElementById(e)}return(e)};
function preventDefault(e){var e=getEvent(e);if(e.preventDefault){e.preventDefault()}e.returnValue=false};
