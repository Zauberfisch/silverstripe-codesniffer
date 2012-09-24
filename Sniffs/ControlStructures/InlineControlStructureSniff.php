<?php
/**
 * SilverStripe_Sniffs_ControlStructures_InlineControlStructureSniff.
 *
 * PHP version 5
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @link      https://github.com/stojg/silverstripe-codesniffer
 */

/**
 * SilverStripe_Sniffs_ControlStructures_InlineControlStructureSniff.
 *
 * Verifies that inline control statements follow the SilverStripe allowed
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @version   Release: dev
 * @link      https://github.com/stojg/silverstripe-codesniffer
 */
class SilverStripe_Sniffs_ControlStructures_InlineControlStructureSniff implements PHP_CodeSniffer_Sniff
{

    /**
     * A list of tokenizers this sniff supports.
     *
     * @var array
     */
    public $supportedTokenizers = array(
                                   'PHP',
                                   'JS',
                                  );

    /**
     * If true, an error will be thrown; otherwise a warning.
     *
     * @var bool
     */
    public $error = true;


    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(
                T_IF,
                T_ELSE,
                T_FOREACH,
                T_WHILE,
                T_DO,
                T_SWITCH,
                T_FOR,
               );

    }//end register()


    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the current token in the
     *                                        stack passed in $tokens.
     *
     * @return void
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr) {
		
        $tokens = $phpcsFile->getTokens();

		// If there are a scope opener "{" we don't need to continuing testing
        if(isset($tokens[$stackPtr]['scope_opener']) === true) {
			return;
		}

		// Ignore the ELSE in ELSE IF. We'll process the IF part later when the
		// parser will sooner or later get to it
		if(($tokens[$stackPtr]['code'] === T_ELSE) && ($tokens[($stackPtr + 2)]['code'] === T_IF)) {
			return;
		}

		// if the control structure have parenthesis, check from the ending one
		if(isset($tokens[$stackPtr]['parenthesis_closer']) === true) {
			$checkPosition = $tokens[$stackPtr]['parenthesis_closer'];
		} else {
			$checkPosition = $stackPtr;
		}
		

		if($tokens[$checkPosition+1]['content'] === "\n") {
			$this->markAsFailed($phpcsFile, $stackPtr, 'Inline control structures with newline before next statement are not allowed.');
			return;
		}

		if($tokens[$checkPosition+2]['code'] === T_WHITESPACE) {
			$this->markAsFailed($phpcsFile, $stackPtr, 'Inline control structures with newline before next statement are not allowed.');
			return;

		}
		return;

    }

	protected function markAsFailed($phpcsFile, $stackPtr, $message) {
		if ($this->error === true) {
			$phpcsFile->addError($message, $stackPtr, 'NotAllowed');
		} else {
			$phpcsFile->addWarning($message, $stackPtr, 'Discouraged');
		}
	}


}
