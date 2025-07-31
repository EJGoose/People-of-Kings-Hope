
# Search for People at Kings Hope Church


## About Project

This tool serves as one page on a wider members area for the users at Kings Hope Church. The aim of this page is to provide a branded, searchable, virtual address book for the members of KHC to access contact details for oneanother. The tool uses Alpine JS and Laravel. It calls the ChurchSuite v2 API to access user data and formats it in a paginated table, ensuring that details are only shared if permission has been given. 

## Tools used

- [Laravel 12](https://laravel.com/docs/12.x)
    - [Guzzle 7](https://docs.guzzlephp.org/en/stable/)
    - [League/Oauth2.0 v2](https://oauth2-client.thephpleague.com/)
    - [ChurchSuite v2 API](https://developer.churchsuite.com/)
- [Alpine JS v3](https://alpinejs.dev/start-here)
- [Tailwind](https://tailwindcss.com/)

## Functionality

The primary function of the app is to request data from the churchsuite database and return it to the user in a visually appealing manner. An initial request is made when the page loads and this is handled by Laravel. After the initial page load, if the user requests a new page of data or submits a search, Alpine JS processes this and makes a request for data from laravel. Laravel then contacts ChurchSuite again and returns the data to Alpine to display. Each item in the table can be expanded to show more contact data where permitted. 


## Issues
Please contact Elliot if you encounter an issue with this tool. 


