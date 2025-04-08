# Use the official PHP Apache image
FROM php:7.4-apache

# Install MySQLi extension
RUN docker-php-ext-install mysqli

# Enable the rewrite module for Apache (useful for clean URLs)
RUN a2enmod rewrite
