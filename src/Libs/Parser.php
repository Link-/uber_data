<?php namespace UberCrawler\Libs;

class Parser {

	protected $_currentPage;

	protected $_nextPage;

	protected $_rawHTMLData;

	protected $_DomDocument;

	protected $_DomXPath;

	public function __construct($html = '') {

		$this->_DomDocument = new \DOMDocument;

		if (!empty($html))
			$this->loadHTML($html);

	}

	public function loadHTML($html) {

		$this->_rawHTMLData = $html;
		$this->_DomDocument->loadHTML($html);
		$this->_DomXPath = new \DomXPath($this->_DomDocument);

	}

	public function getNextPage() {

		$nodes = $this->_DomXPath->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' pagination__next ')]");

		foreach($nodes as $elmnt) {
			var_dump($elmnt->attributes['href']->value);
		}

	}

	public function getDataTableContent() {

		$table = $this->_DomDocument->getElementById('trips-table');

		var_dump($this->getInnerHTML($table));

	}

	protected function getInnerHTML($node) {

    $innerHTML= ''; 
    $children = $node->childNodes; 
    foreach ($children as $child) { 
        $innerHTML .= $child->ownerDocument->saveXML( $child ); 
    } 

    return $innerHTML; 

	}

}