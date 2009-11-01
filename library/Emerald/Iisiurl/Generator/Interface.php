<?php
interface Emerald_Iisiurl_Generator_Interface
{
	/**
	 * Generates an iisiurl from a string
	 *
	 * @param string $iisiurl String to be iisiurlified.
	 */
	public function generate($iisiurl);
}