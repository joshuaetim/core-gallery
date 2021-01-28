# BlogCore

BlogCore is a poorly named photo gallery application, built using several PHP components as opposed to using a framework.

## Local Installation

Simply download or clone the git repository to get started.
```bash
git clone https://github.com/joshuaetim/core-gallery.git
```

## Setup

Create a database named **blogcore** or you can make up yours and edit in *src/Handlers/Database.php*

In your directory, run the following in your command terminal

```bash
php migrations/photos.php
php -S localhost:8080 -t public/ 
```

Head on to your browser with the address http://localhost:8080 and enjoy!

## How It Works
**The idea was to build a modern app without the abstraction of a framework.**

The requests first hit the public/index.php file. This file contains important classes and functions that are used throughout the app.

The app features are:

- Routing (with League\Route)
- Request Handling, Response Generation, and Emitting (with Laminas)
- Uploading of images
- Creation of thumbnails from images
- Edit and Delete Images
- Efficient templating system for View Separation (with Twig)
- Expressive File Handler for most file upload situations
- Elegant Database connections and query models
- Mostly compliant with PSR-7 conventions

The app design is borrowed partly from the Laravel Framework and it uses the Illuminate\Database package to handle database connections. It makes use of the MVC pattern and other groupings such as *Handlers* takes care of specific tasks.

## License
Use anyhow you want. I don't care. Frontend was designed by [HTML5 UP](https://html5up.net/multiverse)