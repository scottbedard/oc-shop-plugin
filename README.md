# oc-shop-plugin

[![Build Status](https://travis-ci.org/scottbedard/oc-shop-plugin.svg?branch=master)](https://travis-ci.org/scottbedard/oc-shop-plugin)
[![Coverage Status](https://coveralls.io/repos/github/scottbedard/oc-shop-plugin/badge.svg?branch=master)](https://coveralls.io/github/scottbedard/oc-shop-plugin?branch=master)
[![Style CI](https://styleci.io/repos/47805210/shield?style=flat)](https://styleci.io/repos/47805210)
[![MIT License](https://img.shields.io/badge/license-MIT-blue.svg)](https://github.com/scottbedard/oc-shop-plugin/blob/master/LICENSE)

This repository is a work in progress, and is not ready for use by anyone.

<a name="local-development"></a>
### Local development

To properly configure the dev environment, create an `.october_proxy` file in `/plugins/bedard/shop` that contains the URL of your local October site. This enables the Webpack dev server to proxy requests to your site while continuing to serve hot-reloadable assets.

To fire up the dev server, run the following command.

```bash
$ npm run dev
```

Production assets can be built with the following command.

```bash
$ npm run build
```
