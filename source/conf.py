# Configuration file for the Sphinx documentation builder.
#
# For the full list of built-in configuration values, see the documentation:
# https://www.sphinx-doc.org/en/master/usage/configuration.html

# -- Project information -----------------------------------------------------
# https://www.sphinx-doc.org/en/master/usage/configuration.html#project-information

project = 'IP Intelligence'
copyright = '2023, Jetcod'
author = 'Hamid Ghorashi'

# -- General configuration ---------------------------------------------------
# https://www.sphinx-doc.org/en/master/usage/configuration.html#general-configuration

extensions = ['sphinx_typo3_theme']

templates_path = ['_templates']
exclude_patterns = []

html_copy_source = False

# -- Options for HTML output -------------------------------------------------
# https://www.sphinx-doc.org/en/master/usage/configuration.html#options-for-html-output

# select the theme
html_theme = 'sphinx_typo3_theme'
html_static_path = ['_static']
