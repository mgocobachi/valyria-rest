# Valyria RESTful APIs

## Creates users, images and comments for the images

## dependencies

    php 5.4+
    phing
    composer

## How to install it

    phing develop
    phing db-install

## If you want to reset the db and clear the cache

    phing db-refresh

Resources:
    php artisan route:list

Basically, this is our routes (application/json, except for image):

        Create an user
        /api/v1/users

        Read an user
        /api/v1/users/1

        Create an image of the user 1
        /api/v1/users/1/image

        Read an image of the user 1
        /api/v1/users/1/image/1

        Create a comment for the image of the user 1
        /api/v1/users/1/image/1/comments

        Read a comment for the image 1 of user 1
        /api/v1/users/1/image/1/comments/1

If a new comment arrive to a specific image,
the owner of the image will receive an email notification

### Powered by Laravel 5 Framework
