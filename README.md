# Uploader-Bot
The application for Resize scheduled images and Upload resized image to cloud storage.

## Installation
- create a database MySQL
- copy project on your server
- copy ./lib/config.sample.inc to ./lib/config.php
- edit ./lib/config.php
- create Dropbox [application](https://www.dropbox.com/developers/apps/)
- create on Dropbox folder "uploader"
- generate on Dropbox Token and edit constant TABLE_QUEUE in ./lib/config.php

## Usage
- run bot

## Available commands: 
- **schedule** Add filenames to resize queue 
- **resize**   Resize next images from the queue 
- **status**   Output current status in format %queue%:%number_of_images% 
- **upload**   Upload next images to remote storage
- **retry**    Re-upload failed images from queue
 
## Description of commands

### Scheduler
> $ bot schedule

### Resizer
> $ bot resize [-n &lt;count&gt;]

### Uploader
> $ bot upload [-n &lt;count&gt;]

### Monitoring
> $ bot status

### Rescheduler
<<<<<<< HEAD
$ bot retry [-n &lt;count&gt;]
=======
$ bot retry [-n &lt;count&gt;]

>>>>>>> origin/master
