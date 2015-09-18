<?php

namespace Lossendae\CommonMark\TaskLists;

use League\CommonMark\ElementRendererInterface;
use League\CommonMark\HtmlElement;
use League\CommonMark\Inline\Element\AbstractInline;
use League\CommonMark\Inline\Renderer\InlineRendererInterface;

/**
 * Class TaskListsCheckboxRenderer
 *
 * @see https://help.github.com/articles/writing-on-github/#task-lists
 * @package Lossendae\CommonMark\TaskLists
 */
class TaskListsCheckboxRenderer implements InlineRendererInterface
{
    /**
     * @param AbstractInline           $inline
     * @param ElementRendererInterface $htmlRenderer
     *
     * @return HtmlElement
     */
    public function render(AbstractInline $inline, ElementRendererInterface $htmlRenderer)
    {
        if (!($inline instanceof TaskListsCheckbox))
        {
            throw new \InvalidArgumentException('Incompatible inline type: ' . get_class($inline));
        }

        $attrs = [];
        foreach ($inline->getData('attributes', []) as $key => $value)
        {
            $attrs[$key] = $htmlRenderer->escape($value, true);
        }

        $attrs['type']     = 'checkbox';
        $attrs['disabled'] = '';
        if ($inline->isChecked())
        {
            $attrs['checked'] = 'checked';
        }

        return new HtmlElement('input', $attrs);
    }
}
