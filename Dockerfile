
# Χρήση μιας επίσημης εικόνας Apache με PHP
FROM php:7.4-apache

# Ενεργοποίηση των απαιτούμενων επεκτάσεων PHP
RUN docker-php-ext-install mysqli

# Αντιγραφή των αρχείων της εφαρμογής στον κατάλογο του Apache
COPY . /var/www/html/

# Ορισμός του σημείου εκκίνησης
EXPOSE 80



