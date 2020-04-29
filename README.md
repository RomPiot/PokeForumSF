# PokeForum

> Oh no, another pokemon forum... 

STOP! PokeForum is not only a forum about Pokemon. <br/>
It's also a real fun game, where every user is a trainer. As a trainer, you can catch Pokemons, earn arena badges, and more.

You can follow the the project management
[here on Trello](https://trello.com/b/mxdZndki/symfony-pokeforum).

## Installation

- Download the zip project
- In the project path, type in the console `composer install` to install all dependencies
- Start the server `symfony serve`
- In your browser, go to https://127.0.0.1:8000/
- Create an account
- If you want to be administrator, edit the bdd.db sql file, and add ROLE_ADMIN in roles array of your user row
- If JS and CSS for EasyAdmin don't load, type in the console `php bin/console assets:install --symlink` to resolve the problem

## Fonctionnalities

### General
- If a user is banned, he is automatically logged out.
- Users with the administrator role can edit all entities with EasyAdmin on the admin link

### Security
- Creation of the User entity with the maker user
- User entity implements the UserInterface and its methods
- Authentication user with username and password from User entity
- Migration of the hash algorithm from bcrypt to auto
- Configuration of the firewall with a guard authenticator to login and logout the user
- Add 3 users roles (admin, modo, user) in the role hierarchy. By default, a user anthenticated have user role
- Add access controle to limit the access depending on the user role
- Controls in front and back to check if the user access is granted for the user connected according to his role

### Forum
- Each user connected can add a new topic
- Each user connected can add a new comment to a topic
- Each topic belongs to a category and a sub-category 
- Topics are displayed in subcategories pages
- Sub-categories are displayed in categories pages
- All users (even unauthenticated users) can read all topics and comments
- Each user connected can view user profiles. Unauthenticated users are redirected to the login page.
- Each user connected can edit its own profile, but not the others
- Each user connected has a rank according to the number of points they have. He gets an extra point each time he adds a topic or a comment.
- Hierarchical relation of the category table with itself relating to its parent category

### Game
- Only available for logged-in users
- Each user is a trainer, and has a maximum of 6 free pokeballs each day
- 8 levels of difficulty according to the user's badges
- Appearance of a random pokemon based on the user's highest badge level
- The higher the pokemon's difficulty, the faster it moves and the faster it runs away
- To catch a pokemon, you have to click on it with the pokeball
- Animation when throwing the pokeball, to find out if you caught it or not
- If 5 pokemon different from the highest level of the user's badge are caught, the user wins a new badge
- If the user has no more pokeball, he can buy 6 new ones (currently for free)
- Display of the trainers' palmares (Top 100), sorted according to their highest badge, and the highest number of different pokemons caught, with filter options
- Display of the complete list of pokemons, with their detailed general information, and with filtering options
- Display full details of a pokemon, including the number of trainers that caught it, and their profiles.
- Display the number of pokemons caught on each trainer's profile, as well as their pokedex details.
- Lot of ajax uery with axios

## Missing / Improvement
- Upload a user profile image
- Send an email to user registration
- Pokeball buying feature
- Donation of a pokeball every 4 hours (the method exist, but need a crontab)
- Pokemon exchanges between trainers, or in exchange for virtual money
- Automatic random pop of a pokemon with no action decided by the user (currently, a pokemon appears when clicking on the "chasser" button, to present the project)
- A better method for calculating rank and adding points on the forum
- Create a pagination on topic list
- Addition of fixtures to automatically add all pokemons, badges, and other data essential to the smooth running of the site when creating the database, to avoid having a syncronized base on github, and conflict on sqlite database with git

## Produced by

- **[Rompiot](https://github.com/RomPiot/)** - *Back-end & Game Developer*
- **[seb7474](https://github.com/seb7474)** - *Designer & Front-end Developer* 

## Thanks

- To our teacher **[Decima](https://github.com/decima)** for his pedagogy and his availability.
- A big thanks to our ghost developer.