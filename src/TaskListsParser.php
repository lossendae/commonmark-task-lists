<?php

namespace Lossendae\CommonMark\TaskLists;

use League\CommonMark\Inline\Parser\AbstractInlineParser;
use League\CommonMark\InlineParserContext;
use League\CommonMark\Delimiter\Delimiter;

/**
 * Class TaskListsParser
 *
 * Works if the characters is surrounded be [ ] and followed by a space
 *
 * @see     https://help.github.com/articles/writing-on-github/#task-lists
 * @package Lossendae\CommonMark\TaskLists
 */
class TaskListsParser extends AbstractInlineParser
{
    /**
     * @return string[]
     */
    public function getCharacters()
    {
        return [' ', 'x'];
    }

    /**
     * @param InlineParserContext $inlineContext
     *
     * @return bool
     */
    public function parse(InlineParserContext $inlineContext)
    {
        $cursor         = $inlineContext->getCursor();
        $delimiterStack = $inlineContext->getDelimiterStack();

        if ($cursor->peek(-1) !== '[')
        {
            return false;
        }

        if ($cursor->peek(-2) == '!' || $cursor->peek(-1) !== '[' || $cursor->peek(1) !== ']' || $cursor->peek(2) !== ' ')
        {
            return false;
        }

        $status = $cursor->peek(0) == 'x';

        $cursor->advanceBy(2);

        // Add entry to stack for this opener
        $delimiter = new Delimiter('[', 1, $inlineContext->getContainer()->firstChild(), true, false, 0);
        $delimiterStack->push($delimiter);

        $opener = $delimiterStack->searchByCharacter(['[']);
        $opener->getInlineNode()->replaceWith(new TaskListsCheckbox($status));

        $delimiterStack->removeDelimiter($opener);

        return true;
    }
}
