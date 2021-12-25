### Some Notes

The project requires that you have PHP8.0 or above, If you wish this to be changed, please let me know.  

You'll see 3 controllers in Http/Controllers, they are there only for testing purposes,  
`UserImposterController` is first used to create a new user in the database: `POST: /users/create`  
`CommentWrittenImposterController` is used to make comments on behalf of a user: `POST: /users/{id}/comment`  
`LessonWatchedImposterController` is used to create a lesson and then mark it as watched: `POST: /users/{id}/watch`  
