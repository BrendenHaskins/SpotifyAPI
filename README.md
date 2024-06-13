# SpotifyAPI
SPOTTED:
A wordle clone for music artists, using Spotify's API to gather data about an artist for reference.
Brenden Haskins, Evan Thomas, Mason Sain
1. Separates all database/business logic using the MVC pattern.
See views folder, Controllers folder, classes folder, and index. 
index sends routes to the controller, which renders html from the views folder.

2. Routes all URLs and leverages a templating language using the Fat-Free framework.

all relative URLs are set from the index, and views utilize handlebar notation to get data from the f3 hive.

3. Has a clearly defined database layer using PDO and prepared statements.

See model directory which includes query file for PDOs with prep and bind statements.

4. Data can be added and viewed.
Users can make their accounts and see their score.
5. Has a history of commits from all team members to a Git repository. Commits are clearly commented.
See commit history for each teammate's 

6. Uses OOP, and utilizes multiple classes, including at least one inheritance relationship.
LinkHandler extends BaseHandler to make requests to Spotify's v1 API.
Also see guess and hidden song, user and admin.
7. Contains full DocBlocks for all PHP files and follows PEAR standards.
See comment headers and method headers
8. All code is clean, clear, and well-commented. DRY (Don't Repeat Yourself) is practiced.
    Many fragments of code are extracted to one method to call multiple times.
9. Your submission shows adequate effort for a final project in a full-stack web development course.
Our project shows our common love for music combined with our purusal of a well balanced background in tech. See the commit history to see our continued commitment to the project over time, and how often we contributed.

## SESSION variables
<img width="706" alt="Screenshot 2024-06-13 at 10 51 09 AM" src="https://github.com/BrendenHaskins/SpotifyAPI/assets/93852560/932f2c28-2fea-4115-9238-69c39199852f">

## UML Diagram
<img width="787" alt="Screenshot 2024-06-13 at 10 50 58 AM" src="https://github.com/BrendenHaskins/SpotifyAPI/assets/93852560/eed6d74a-184a-42b4-b2fa-0af1d4d92308">


