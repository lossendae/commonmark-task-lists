# CommonMark task Lists

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/lossendae/commonmark-task-lists/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/lossendae/commonmark-task-lists/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/lossendae/commonmark-task-lists/badges/build.png?b=master)](https://scrutinizer-ci.com/g/lossendae/commonmark-task-lists/build-status/master)

Implements github flavored task-lists to phpleague/commonmark : https://help.github.com/articles/writing-on-github/#task-lists)

## Installation

This project can be installed via Composer:

```
composer require lossendae/commonmark-task-lists
```

## Usage

```php
use League\CommonMark\Converter;
use League\CommonMark\DocParser;
use League\CommonMark\Environment;
use League\CommonMark\HtmlRenderer;
use Lossendae\CommonMark\TaskLists\CloseTaskListsParser;
use Lossendae\CommonMark\TaskLists\TaskListsCheckboxRenderer;

$environment = Environment::createCommonMarkEnvironment();
$environment->addInlineRenderer('Lossendae\CommonMark\TaskLists\TaskListsCheckbox', new TaskListsCheckboxRenderer());
$environment->addInlineParser(new CloseTaskListsParser());

$converter = new Converter(new DocParser($environment), new HtmlRenderer($environment));

echo $converter->convertToHtml('# Hello World!');
```

## Syntax

Lists can be turned into task lists by prefacing list items with [ ] or [x] (incomplete or complete, respectively).

```
- [x] @mentions, #refs, [links](), **formatting**, and <del>tags</del> are supported
- [x] list syntax is required (any unordered or ordered list supported)
- [x] this is a complete item
- [ ] this is an incomplete item
```

Task lists render with checkboxes once parsed by CommonMark. Select or unselect these checkboxes to mark them as complete or incomplete in your document.

Task lists can be nested to better structure your tasks:

```
- [ ] a bigger project
  - [ ] first subtask #1234
  - [ ] follow up subtask #4321
  - [ ] final subtask cc @mention
- [ ] a separate task
```

Task lists can be nested to arbitrary depths, though we recommend nesting at most once or twice; more complicated tasks should be broken out into separate lists.