<?php
class Emerald_View_Helper_FormOptionsSitemap
{
	
	private $_sitemap;
	private $_activeId = null;
	private $_level = -1;
	
	public function formOptionsSitemap(Emerald_Sitemap $sitemap, $activeId = null)
	{
		$this->_sitemap = $sitemap;
		$this->_activeId = $activeId;
		try
		{
			return $this->_renderOptions(null);
		}catch(Exception $e){ 
			return ""; 
		}
	}
	
	
	private function _renderOptions($branchId)
	{
		$this->_level++;
		$output = "";
		$branch = $this->_sitemap->findBranch($branchId);
		
		foreach($branch as $node) {
			$selected = ($node->id == $this->_activeId) ? 'selected="selected"' : '';
			$output .= "<option value=\"{$node->id}\" {$selected}>";
			
			$output .= str_repeat(" - ",$this->_level);
			$output .= $node->title;
			$output .= "</option>\n"; 
			$output .= $this->_renderOptions($node->id);
			
			
		}
		$this->_level--;
		return $output;
	}
	/*
	private function _renderBranch($branchId)
	{
		$this->_level++;
		$branch = $this->_sitemap->findBranch($branchId);
		$output = "";
		foreach($branch as $node) {
			$class = ($node->id == $this->_activeId) ? 'active' : 'nonactive';
			$output .= "<li class=\"{$class}\">";
			
			$output .="<a class=\"filelibFolder\" href=\"/emerald-admin/filelib/id/{$node->id}\">{$node->title}</a>";
			
			$output .= $this->_renderBranch($node->id);
			
			$output .= "</li>\n"; 
		}
		$output .= '</ul>';
		$this->_level--;
		return $output;
	}
	*/
}

?>