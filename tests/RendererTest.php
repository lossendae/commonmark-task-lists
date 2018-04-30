<?php declare(strict_types = 1);

use League\CommonMark\Converter;
use League\CommonMark\DocParser;
use League\CommonMark\Environment;
use League\CommonMark\HtmlRenderer;
use Lossendae\CommonMark\TaskLists\TaskListsParser;
use Lossendae\CommonMark\TaskLists\TaskListsCheckboxRenderer;
use PHPUnit\Framework\TestCase;

final class RendererTest extends TestCase
{
    private $converter;

    public function setUp()
    {
        $environment = Environment::createCommonMarkEnvironment();
        $environment->addInlineRenderer('Lossendae\CommonMark\TaskLists\TaskListsCheckbox', new TaskListsCheckboxRenderer());
        $environment->addInlineParser(new TaskListsParser());

        $this->converter = new Converter(new DocParser($environment), new HtmlRenderer($environment));
    }

    public function testTodo()
    {
        $actual = $this->converter->convertToHtml('- [ ] todo');
        $expected = <<<HTML
<ul>
<li>
<input type="checkbox" disabled=""></input> todo</li>
</ul>

HTML;

        $this->assertEquals($actual, $expected);
    }

    public function testDone()
    {
        $actual = $this->converter->convertToHtml('- [x] done');
        $expected = <<<HTML
<ul>
<li>
<input type="checkbox" disabled="" checked="checked"></input> done</li>
</ul>

HTML;

        $this->assertEquals($actual, $expected);
    }

    public function testComplete()
    {
        $actual = $this->converter->convertToHtml(<<<MARKDOWN
- [x] @mentions, #refs, [links](), **formatting**, and <del>tags</del> are supported
- [x] list syntax is required (any unordered or ordered list supported)
- [x] this is a complete item
- [ ] this is an incomplete item
MARKDOWN
);
        $expected = <<<HTML
<ul>
<li>
<input type="checkbox" disabled="" checked="checked"></input> @mentions, #refs, <a href="">links</a>, <strong>formatting</strong>, and <del>tags</del> are supported</li>
<li>
<input type="checkbox" disabled="" checked="checked"></input> list syntax is required (any unordered or ordered list supported)</li>
<li>
<input type="checkbox" disabled="" checked="checked"></input> this is a complete item</li>
<li>
<input type="checkbox" disabled=""></input> this is an incomplete item</li>
</ul>

HTML;

        $this->assertEquals($actual, $expected);
    }

    public function testNested()
    {
        $actual = $this->converter->convertToHtml(<<<MARKDOWN
- [ ] a bigger project
  - [ ] first subtask #1234
  - [ ] follow up subtask #4321
  - [ ] final subtask cc @mention
- [ ] a separate task
MARKDOWN
);
        $expected = <<<HTML
<ul>
<li>
<input type="checkbox" disabled=""></input> a bigger project
<ul>
<li>
<input type="checkbox" disabled=""></input> first subtask #1234</li>
<li>
<input type="checkbox" disabled=""></input> follow up subtask #4321</li>
<li>
<input type="checkbox" disabled=""></input> final subtask cc @mention</li>
</ul>
</li>
<li>
<input type="checkbox" disabled=""></input> a separate task</li>
</ul>

HTML;

        $this->assertEquals($actual, $expected);
    }
}
