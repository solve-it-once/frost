[![License: GPL](https://img.shields.io/badge/license-GPL-blue)](https://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html)
![GitHub top language](https://img.shields.io/github/languages/top/solve-it-once/frost)
![GitHub language count](https://img.shields.io/github/languages/count/solve-it-once/frost)

# Frost: Low-code Drupal for your whole team

[Frost](https://frostdrupal.com/) is a component-forward Drupal install profile. It's made for teams and agencies that want
to launch quickly, build Drupal-based functionality on top, and maintain the codebase in the long term with less frustration.

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

Node configurations can be cloned, and the common field settings are easy enough to add and remove from bundle to bundle.

  * **Article** — Serialized storytelling content
  * **Event** — When the time and location are important to the story
  * **Page** — Catch-all type for pages and wrappers

#### Components (paragraphs)

By making most content use a consistent set of Atomic Design components, we can ensure content site-wide abides by the
directives of your design system.

All the components are Paragraphs including the layout mechanism, so tools like Gutenberg and Layout Builder aren't required.

##### Sectioning

  * **Stripe: Atomic** — The _cornerstone_ of the design system; an infinitely-flexible section container
  * **Layout** — Ability to create layouts of any number of columns, and can be nested for complex use-cases
    * **Column** — Tiny holder of any components used elsewhere
  * **Stripe collection** — Reuses the stripe concept to allow content creators to make tabs and slideshows

The "Stripe: Atomic" sectioning container gives you a lot of flexibility to build out experiences, but could be a ton of
work if every section on the site is different. For designs with consistent-or-alternating sections you can easily **take
a "balanced breakfast" approach** and create other 'Stripe' sections like "Stripe: Image + text" or similar.

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

@todo

## Customizing

@todo

## Reasoning

Why would you choose frost instead of another installation profile, CMS, or SaaS product? Why does frost do things the
way it does?

  1.
