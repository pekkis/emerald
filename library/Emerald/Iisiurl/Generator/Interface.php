<?php
interface Emerald_beautifurl_Generator_Interface
{
	/**
	 * Generates an beautifurl from a string
	 *
	 * @param string $beautifurl String to be beautifurlified.
	 */
	public function generate($beautifurl);
}