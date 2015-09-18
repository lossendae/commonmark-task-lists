<?php

namespace Lossendae\CommonMark\TaskLists;

use League\CommonMark\ContextInterface;
use League\CommonMark\Inline\Parser\AbstractInlineParser;
use League\CommonMark\InlineParserContext;
use League\CommonMark\Util\ArrayCollection;

/**
 * Class CloseTaskParser
 *
 * @see https://help.github.com/articles/writing-on-github/#task-lists
 * @package Lossendae\CommonMark\TaskLists
 */
class CloseTaskListsParser extends AbstractInlineParser
{

    /**
     * @return string[]
     */
    public function getCharacters()
    {
        return [']'];
    }

    /**
     * @param ContextInterface    $context
     * @param InlineParserContext $inlineContext
     *
     * @return bool
     */
    public function parse(ContextInterface $context, InlineParserContext $inlineContext)
    {
        $cursor = $inlineContext->getCursor();
        $inlines = $inlineContext->getInlines();

        if ($cursor->peek(-2) !== '[' || $cursor->peek(-3) == '!' || $cursor->peek(1) !== ' ')
        {
            return false;
        }

        if (!in_array($cursor->peek(-1), [' ', 'x']))
        {
            return false;
        }

        $status = $cursor->peek(-1) == 'x';

        // Remove markdown tag
        $this->nullify($inlines, 0, $inlines->count());

        $cursor->advance();

        $inlines->add(new TaskListsCheckbox($status));

        return true;
    }

    /**
     * @param ArrayCollection $collection
     * @param int             $start
     * @param int             $end
     */
    protected function nullify(ArrayCollection $collection, $start, $end)
    {
        for ($i = $start; $i < $end; $i++)
        {
            $collection->set($i, null);
        }
    }
}
