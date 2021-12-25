### Some Notes

This project requires that you have PHP8.0 or above, If you wish this to be changed, please let me know.  

Create an `.env` file and make sure your database credentials are correct and run:

```
php artisan migrate:fresh
php artisan serve
```

You'll see 3 controllers in Http/Controllers, they are there only for testing purposes,  

UserImposterController is first used to create a new user in the database

`POST: /users/create`

CommentWrittenImposterController is used to make comments on behalf of a user

`POST: /users/{id}/comment`  

LessonWatchedImposterController is used to create a lesson and then mark it as watched

`POST: /users/{id}/watch`  


```
curl --location --request POST 'http://127.0.0.1:8000/users/create'
curl --location --request POST 'http://127.0.0.1:8000/users/1/watch'
curl --location --request POST 'http://127.0.0.1:8000/users/1/comment'
```

https://user-images.githubusercontent.com/15141224/147383024-0c804a97-57c6-47eb-86a7-2ff62c894120.mp4

