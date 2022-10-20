# Capital-IQ
Code for dynamic link logs for the S&amp;P Captial IQ online resource

## File Overview
1. capitalIQ.html - contains the html, css, and js to display a list of links.
2. caiptalIQ.php - contains the code that is called when a link is clicked. This code logs the link name and timestamp in a persistent JSON file to track which links are clicked.This code also returns the contents of the JSON file to the html to update the styling of which links are clicked
3. clearLinks.php - this code is meant to be setup as a cronjob run every 30 minutes. This script will compare the click timestamp with the current time and clear the "clicked" status of links that were not clicked in the last 30 minutes.
