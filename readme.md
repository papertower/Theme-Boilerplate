# Paper Tower Boilerplate Theme
Welcome! This theme was created, with love, by [Paper Tower](https://papertower.com). It may seem a bit strangely set up, but hopefully this document will clear some things up. The goal behind setting the theme up this way is for two purposes:

1. Organization
2. Unit Testing

There's so much miscellaneous stuff that goes into every theme. Knowing where things go can be a pain. This attempts to resolve that. From there, we want to make it possible to unit test all of this in the future, making the themes even more robust and safe to update. The OOP model lends itself to convenient unit testing.

# Getting Started

## Setting Up Locally
Everything can be done from the command-line. First, make sure you have the following installed on your local machine:
- [Nodejs](https://nodejs.org/en/)
- [Composer](https://getcomposer.org/doc/00-intro.md)

From there, cd into the theme directory on the command-line and, the first time only run the following commands:
```
npm install
composer install
```
This will install the necessary node and composer modules to get working. If more packages are added during development, it may be necessary to run either of those commands, again.

## Compiling Sass and JavaScript
There are really two scenarios for compiling Sass and JS. First is when you're working and building things. Second is when you're finished and are pushing to the repository and/or production.

### Working Locally
In this scenario, the compiled code will be an expanded, easy to ready format that's useful for debugging. To begin, run the following from the theme directory:
```
npm run watch
```
This will turn on the watching of files and provide you with URL's for testing locally as well as on devices on your local network. Live reload is enabled so simply save files and any browser or device viewing the website will automatically refresh to reflect the changes.

### Finalizing Changes
Once you're finished and are ready to commit your work, run the following command:
```
npm run production
```
This will create optimized and minified versions of the code that are production-ready. You only need to run this once before committing.

## File Organization & Namespaces

### Assets
All files that are intended to be downloaded from the browser (assets) such as fonts, images, js, and css files, are in the `assets` directory.

### Src
All non-template PHP files for managing and configuring the theme are in the `src` directory. This follows the [PSR-4](http://www.php-fig.org/psr/psr-4/) autoloader standard with `Theme` as the base namespace. So, for example, the `Media` class found in `src/Configuration/Media` belongs to the namespace `Configuration\Media`. What's great about this is that if you see a class used you can easily find where the file is from the namespace. Brilliant!

If namespaces are new to you, take a crash course in the [PHP Namespace Documentation](http://php.net/manual/en/language.namespaces.php).

### Views
The default WordPress template structure is a mess. It all goes in the theme root and can quickly become overwhelming. For that reason, this theme _allows_ for an alternative template structure.

Every template, except for header.php and footer.php (due to WP limitations) are contained in the `views` directory. The directory is broken up into `Base`, `Partials`, `Post Type`, `Taxonomy`, and `Sidebar`. If you look in the folders you'll find it's fairly intuitive, the only real sections that need attention are for post types and taxonomies.

Post types and taxonomies follow the same file naming convention as before, except custom post types don't need the post type in the file name, such as `single-projects.php` — instead that would be `views/Post Type/projects/single.php`. Same with the archive. Unique files, such as `page.php` remain the same, so that would be `views/Post Type/page/page.php`. This applies to `category.php`, `attachment.php`, etc. Follow the WordPress template naming convention.

Custom templates also got a slight improvement. Now, when a template (with the `/** Template Name **/` comment block) is inside of a post type's directory, it's automatically associated to that post type. Adding the `Template Post Type` won't hurt anything.

Any WordPress template not mentioned is, by default, in the Base directory. Remember, too, that the normal root structure is still supported, if all else fails.

# Quick Reference & FAQ
The following should help you find what you're looking for quickly. If it's not, we need to improve something.

## Adding Image Sizes
Go to `/src/Configuration/Media` and modify the `add_image_sizes` method.

## Adding Post Types
In the `src/PostType` directory duplicate the `Example.php` (or an existing) post type file. Then change the `SLUG` constant and set the labels and registration arguments. Give the file and post type a unique name that matches the post type name.

Once you're finished, go to the `src/Theme.php` file and add the post type class to the array found in the `get_custom_post_type_services()` method.

## Extending Existing Post Types
This is just about the same as adding a custom post type. The only difference is that you don't need to extend `BasePostType` and therefore don't need the extra methods from the abstract class. Just add the register method and do whatever you need to do!

Once you're ready, add it to the `get_extended_post_type_services()` method found in `src/Theme.php`.

## Command to compile and watch files for local work
```
npm run watch
```

## Command to compile Sass & JS for committing
```
npm run production
```

# Roadmap
Presently much of this code is procedural in an object-oriented factory format. While that's certainly not taking full advantage of OOP patterns, getting the codebase in an OOP structure was good step forward. Over time it would be good to explore the following opportunities:

- [ ] Create an Asset class which loads a single asset and may be loaded in places like the post types
- [ ] Consider testing out [Timber](https://www.upstatement.com/timber/)
