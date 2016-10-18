# earthquake-legacy-usdesign
Legacy tool used for computing seismic design values


## Docker

### Building an image

- From root of project, run:
    ```
    docker build -t usgs/earthquake-legacy-usdesign:latest .
    ```

### Running a container

- Start the container using the image tag
    ```
    docker-compose up
    ```

- Connect to running container in browser
  ```
  http://localhost:8110/designmaps/us/application.php
  ```