# Contributing to WPAdCenter ✨

WordPress ad manager plugin that can display both Google AdSense and banner ads :)

There are many ways to contribute to the project!

- [Translating strings into your language](https://github.com/wpeka/wpadcenter/wiki/).
- Answering questions on the various WPAdCenter communities like the [WP.org support forums](https://wordpress.org/support/plugin/wpadcenter/).
- Testing open [issues](https://github.com/wpeka/wpadcenter/issues) or [pull requests](https://github.com/wpeka/wpadcenter/pulls) and sharing your findings in a comment.
- Submitting fixes, improvements, and enhancements.

If you wish to contribute code, please read the information in the sections below. Then [fork](https://help.github.com/articles/fork-a-repo/) WPAdCenter, commit your changes, and [submit a pull request](https://help.github.com/articles/using-pull-requests/) 🎉

## Getting started

- [How to set up WPAdcenter development environment](https://github.com/wpeka/wpadcenter/wiki/How-to-set-up-WPAdcenter-development-environment)
- [Git Flow](https://github.com/wpeka/wpadcenter/wiki/)
- [Minification of SCSS and JS](https://github.com/wpeka/wpadcenter/wiki/Minification-of-SCSS-and-JS)
- [Naming conventions](https://github.com/wpeka/wpadcenter/wiki/Naming-conventions)
- [String localisation guidelines](https://github.com/wpeka/wpadcenter/wiki/String-localisation-guidelines)
- [Running unit tests](https://github.com/wpeka/wpadcenter/blob/trunk/tests/README.md)
- [Running e2e tests](https://github.com/wpeka/wpadcenter/blob/trunk/tests/e2e/README.md)

## Coding Guidelines and Development 🛠

- Ensure you stick to the [WordPress Coding Standards](https://make.wordpress.org/core/handbook/best-practices/coding-standards/php/)
- Run our build process described in the document on [How to set up WPAdcenter development environment](https://github.com/wpeka/wpadcenter/wiki/How-to-set-up-WPAdcenter-development-environment), it will install our pre-commit hook, code sniffs, dependencies, and more.
- Whenever possible please fix pre-existing code standards errors in the files that you change. It is ok to skip that for larger files or complex fixes.
- Ensure you use LF line endings in your code editor. Use [EditorConfig](http://editorconfig.org/) if your editor supports it so that indentation, line endings and other settings are auto configured.
- When committing, reference your issue number (#1234) and include a note about the fix.
- Ensure that your code supports the minimum supported versions of PHP and WordPress; this is shown at the top of the `readme.txt` file.
- Push the changes to your fork and submit a pull request on the trunk branch of the WPAdCenter repository.
- Make sure to write good and detailed commit messages (see [this post](https://chris.beams.io/posts/git-commit/) for more on this) and follow all the applicable sections of the pull request template.
- Please avoid modifying the changelog directly or updating the .pot files. These will be updated by the WPeka team.

## Feature Requests 🚀

Feature requests can be [submitted to our issue tracker](https://github.com/wpeka/wpadcenter/issues/new). Be sure to include a description of the expected behavior and use case, and before submitting a request, please search for similar ones in the closed issues.
