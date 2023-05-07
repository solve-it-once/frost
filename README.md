[![License: GPL](https://img.shields.io/badge/license-GPL-blue)](https://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html)
![GitHub top language](https://img.shields.io/github/languages/top/solve-it-once/frost)
![GitHub language count](https://img.shields.io/github/languages/count/solve-it-once/frost)

# Frost: Low-code Drupal for your whole team

[Frost](https://frostdrupal.com/) (even more info [on drupal.org](https://www.drupal.org/project/frost)) is a component-forward 
Drupal install profile. It's made for teams and agencies that want to launch quickly, build Drupal-based functionality on top, 
and maintain the codebase in the long term with less frustration.

[![Inline editing right out of the  box](https://i.imgur.com/ZRTjVma.png)](https://github.com/solve-it-once/frost)

On installation, you have the components, configurations, content, and instructions to make a _launchable_ brochure site
without fuss, so your team can focus on advanced functionality.

## Features

All the awesome stuff that comes with [Drupal](https://drupal.org/) and contrib, plus:

- [x] **Default content** — Template pages for Home, 403/404, plus boilerplate
- [x] **Components** — Paragraphs with lots of buttons and dials for styling
- [x] **Views** — "Related content", paged listings, Admin management pages, and more
- [x] **Commerce** — Installed and baseline configured, with products working just like nodes and terms
- [x] **SEO** — Tactically developed for power and ease of management
- [x] **Search** — Full `search_api_solr` index configuration, View, and blocks
- [x] **Display modes** — Weeks worth of configuration for all entity types, both form and view modes
- [x] **Theming** — Directly customize the included theme instead of fighting against a base
- [x] **DX** — Well-documented custom code, host configs, and a little bit of magic
- [x] **Content management!** — Reuse components, clone pages, edit inline, and more creature comforts

One more thing: **Heroes**. Content, terms, and products all have a hero that shows the page title by default, but a
_Hero display mode_ can be used anywhere to override the hero layout, **or** a custom Paragraphs-based hero with all the
styling options (including looped background videos!) can be dropped in, all without calling on a developer.

### Installed entity types + bundles

Theses are the kinds of things every site should need, so frost makes them available on day one:

#### Content (node)

Node configurations can be cloned, and the common field settings are easy enough to add and remove from bundle to
bundle.

* **Article** — Serialized storytelling content
* **Event** — When the time and location are important to the story
* **Page** — Catch-all type for pages and wrappers

#### Components (paragraphs)

By making most content use a consistent set of Atomic Design components, we can ensure content site-wide abides by the
directives of your design system.

All the components are Paragraphs including the layout mechanism, so tools like Gutenberg and Layout Builder aren't
required.

##### Sectioning

* **Stripe: Atomic** — The _cornerstone_ of the design system; an infinitely-flexible section container
* **Layout** — Ability to create layouts of any number of columns, and can be nested for complex use-cases
  * **Column** — Tiny holder of any components used elsewhere
* **Stripe collection** — Reuses the stripe concept to allow content creators to make tabs and slideshows

The "Stripe: Atomic" sectioning container gives you a lot of flexibility to build out experiences, but could be a ton of
work if every section on the site is different. For designs with consistent-or-alternating sections you can easily *
*take a "balanced breakfast" approach** and create other 'Stripe' sections like "Stripe: Image + text" or similar.

##### Storytelling

* **Accordion** — Expando for FAQs and other disclosures
* **Block** — System, custom, and dynamic blocks
* **Blockquote** — Rich text as a pull-quote
* **Cards** — A side-by-side layout of cards that don't represent other site content
  * **Card** — Optional image, title, text, and link
  * **Card: Percent** — Highlight a top-line number, with optional animation
* **Content listing** — Select one or more node teasers
* **Heading** — h1-h6, with plenty of styling options
* **iframe** — Embedded pages from elsewhere
* **Link** — Themed, configurable buttons
* **Media** — Captioned core media: audio, document, image, video, and remote video
  * **Hotspot** — Responsive tag/label with tooltip for various media
* **Media listing** — For making galleries and slideshows from media
* **Message** — Place dismissible system messages anywhere on the page
* **Product listing** — Side-by-side teasers of products for CTAs and the like
* **Rich text** — WYSIWYG CKEditor for everyday HTML
* **Spacer** — Add extra, responsive vertical space where desired
* **Table** — Data tables with _built-in interactive chart capabilities_
* **Term listing** — Side-by-side teasers for your tags
* **User listing** — Side-by-side teasers for your people
* **View** — Flexible displays that can be context-aware, even in the middle of content
* **Webform** — Put contact and custom forms anywhere you want!

##### Utility

* **SEO** — Title tag, description, tags, noindex/nofollow, and social image fields
* **From library** — Using Paragraphs library

#### Product

* **Default** — It's tough to know what your store sells, so this is a placeholder for components

### Taxonomy

* **Article type** — Differentiate articles as blogs, news, press releases, etc.
* **Tag** — Freeform description of content

## Installation

This guide is intended to get you going with a site quickly, and assumes a moderate amount of technical knowledge, such
as the ability to install dependencies using a nix-style package manager.

### Prerequisites:

1. [git](https://git-scm.com/book/en/v2/Getting-Started-Installing-Git)
2. [lando](https://docs.lando.dev/getting-started/installation.html) (and docker, among lando's dependencies)
3. A text editor or IDE such as vscode, PHPStorm, etc.
4. For additional advanced functions, a PHP CLI, composer, and other tools locally rather than in the containers

You'll probably have luck cloning the repo in step 2 of the local steps below, but if not then you'll want to make an
[account on GitHub](https://github.com/join) and
[add a working SSH public key to your account](https://docs.github.com/en/authentication/connecting-to-github-with-ssh/adding-a-new-ssh-key-to-your-github-account).

### Local machine installation

In the future we may automate more of these steps, but for now it's useful to walk through them all to ensure
success, and to drill down into points where the process could tentatively fail for you.

If you follow these steps and run into difficulty, please add an issue to this repository with a detailed explanation of
what went wrong, so we can fix it and amend the documentation further. Thank you.

1. `cd` to where you want to store the repository on your computer (if unsure, `cd ~` then `mkdir htdocs`
   then `cd ~/htdocs`)
2. `git clone git@github.com:solve-it-once/frost.git nameofyoursite` (where 'nameofyoursite' is the site name with just
   letters/numbers/underscores)
3. `cd nameofyoursite` to enter the repository
4. `vi .git/config` (or use your text editor of choice) and save the file with "filemode = false" under core
5. `vi .lando.yml` and change the three places where the word 'frost' is to be 'nameofyoursite' instead
6. `vi blt/example.local.blt.yml` and change the two instances of 'frost' to 'nameofyoursite'
7. `lando start` to create and begin running the application's containers
8. `lando composer install` to ensure the dependencies are available. lando start tries to do this but sometimes fails
9. (optional, potentially frustrating) `lando blt blt:init:git-hooks` to validate your commits (you may be prompted to
   provide feedback data to the BLT maintainers)
10. `lando blt source:build` to move the settings files into place, among other automated activities
11. `lando drush sql-connect` to confirm the mysql command has plausible looking database, user, and password values
12. `lando drush si` to install Drupal using the frost install profile/distribution. Say yes to delete the database, and
    ignore errors and warnings. **This command will tell you the admin usernsmae and password. Copy these and retain them
    for your records**
13. `lando drush en frost_default_content` to install the pages and examples for a head start
14. (optional) `lando drush pmu frost_default_content` to keep the installed modules clean
15. `lando drush uli` to get a URL you can copy and then paste into your browser to log into your new site. If this doesn't
    work, use the admin username and password from step 12
16. When you paste the URL into your browser, you may get a warning about security. This is due to the local site using
    a self-signed certificate and is nothing to worry about. Open the advanced options accordion and click the link to
    proceed (this may take a few seconds)
17. Once you've proceeded and see the first screen of the site as a logged-in admin user, it should be the
    path `/user/1/edit`. On the User #1 edit screen, be sure to at least enter a name in the name field, and ideally the
    other profile fields
18. At the very top left of the User #1 edit screen, click the Drupal drop to go to the home page. You're frost-ing now!

### Post-install

The exact steps to take your local dev site and turn it into a live website on the public internet varies by which
hosting service provider you choose, but the following rough guidelines could help in setting up your site and workflow:

* Create a 'nameofyoursite' git remote repository on GitHub, bitbucket, gitlab, or another service
* Follow the service-provided instructions to add/replace the remote in your `.git/config` file for the repo
* Use `lando drush cex -y` to export your site's configuration. **This is important to do early** as the repo contains
  configurations that may not be importable. Exporting early can save some heartache
* Try `lando blt validate` to confirm all the twig, yaml, PHP, and other files are formatted correctly
* Do the usual git stuff to add and commit the changes, then push to your site-specific remote
* `lando drush sql-dump --result-file=nameofyoursite_local_YYYYMMDD_0.sql` will save the file in `docroot/`. I usually
  move it to the repo root to make it easy to restore
* Set up a remote host and then sync the database and files to it (you will want to swap the `frost.site.yml`
  in `drush/sites` for a yml file containing your new host's correct drush aliases)

If you go through the process of deploying a frost site to a hosting service without detailed instructions in this
readme, the project would love your contribution of tweaked configuration files and step-by-step instructions! Presently
frost intends to support:

* platform.sh
* Pantheon
* Acquia Cloud (Next)

...though other services or a plain LAMP hosting server are also welcome.

### Kill it with fire!

If you're done evaluating frost and want to delete the nameofyoursite instance off your laptop, you can do the
following:

1. `lando destroy -y` (this kills the crab)
2. `cd ..` to leave the nameofyoursite folder
3. `rm -rf nameofyoursite/`

## Customizing

Much of the site-specific work you'll do is standard Drupal site-building, like making or cloning new content and other
entity types and assigning fields, form widgets, and view modes for them, but there are a few places primarily in
frost_theme where small tweak can really make the site look like your own:

* First, from the site itself, changing the site name and info at `/admin/config/system/site-information` is a good
  start
* Then, the sitewide contact information at `/admin/content/contact-info` is a quick win. Aren't the social icons cool?
* Obviously edit the content to suit your site, including the Paragraphs library items at `/admin/content/paragraphs`
* In code, replace apple-touch-icon.png and favicon.ico (and the same files in `docroot/`) with your own branding
* If you intend to use behat and phpunit tests, the structure of the root `tests/` directory is probably familiar
* Most customization of code will occur in `docroot/themes/custom/frost_theme`. The intention is for you to edit the
  theme directly, rather than creating a subtheme or anything else similarly bothersome
* At `docroot/themes/custom/frost_theme` replace the logo images at the theme root and in the `images/` sub-folder(s)
  with your own branding
* Edit the custom properties (CSS variables) in `docroot/themes/custom/frost_theme/css/settings.css` to match your
  brand's colors. If your brand includes a shade of blue, use that for the "Main" color. For the most part just follow
  the file's lead
* Edit the other CSS files to directly change various components and utility classes as desired
* It's usually not necessary to edit any of the twig templates, so before opening a template file try to find the
  applicable preprocess function in frost_theme.theme or the `includes/` directory
* Often the easiest way to add styling and functionality is to create a new component or extend an existing one, often
  using 'Magic' field machine names to include utility classes, HTML attributes, or `data-` attributes that the theme
  CSS/JS can pick up
* The codebase is yours now, so please take the above merely as guidelines, and develop the site how you see fit!

## Reasoning

Why would you choose frost instead of another installation profile, CMS, or SaaS product? Why does frost do things the
way it does?

1. Drupal is a great content management framework for building sophisticated applications
2. However, Drupal does not have a built-in site builder tool like Wordpress's block editor
3. Tools like layout builder that other Drupal install profiles use, in my (Brad's) opinion, are more frustrating than
   they are powerful, while the Paragraphs contrib module works great
4. Most designs most of the time can be replicated using a component system of about 25 base-level components including
   stripe backgrounds and flex layouts
5. Utility-first CSS works great with Drupal, albeit the buildout can be time-consuming, so frost is intended to save
   that time
6. With the storytelling and base site-building out of the way, the development on a project can be branding and then
   advanced functionality. Previous time-sinks like frontend development are diminished that way
7. JavaScript functionality can also be atomic, in a sense, by leaning on MutationObservers, bubbled events, and
   initialization in the lifecycle. See `docroot/themes/custom/frost_theme/js/partials` for lots of examples
8. Having a bunch of structure and functionality built out in a best practices manner can help additional work follow
   the same good patterns
9. By golly, every site's gonna have a Privacy Policy and stuff. Might as well include all these pages from the get-go,
   because making them again is a real drag
