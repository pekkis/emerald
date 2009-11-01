<?php
class Emerald_View_Helper_FilelibTree
{
	
	private $_tree;
	private $_level = 0;
	
	public function filelibTree($tree, $active = null, $action = 'index')
	{
		$this->_tree = $tree;
		$this->_active = $active;
		$this->_action = $action;
		
		return $this->_renderBranch($tree['']);
	}
	
	
	private function _renderBranch(&$branch)
	{
		
		if(!$branch) {
			return '';
		}
		
		$output = '<ul>';
		foreach($branch as $node) {
			$class = ($node->id == $this->_active) ? 'active' : 'nonactive';
			$output .= "<li class=\"{$class}\">";
			
			if($class != 'active')
				$output .= "<img src=\"/lib/gfx/nuvola/22x22/filesystems/folder_open.png\" align=\"absmiddle\" /> ";
			else
				$output .= "<img src=\"/lib/gfx/nuvola/22x22/filesystems/folder.png\" align=\"absmiddle\" /> ";
			
			$output .="<a class=\"filelibFolder\" href=\"/admin/filelib/{$this->_action}/id/{$node->id}\">{$node->name}</a>";
			
			$output .= "&nbsp;" . $this->view->iconSmall("actions/configure", "admin/filelib/folder_properties", Array('popupSmall'), "", "/admin/filelib/folder_properties");
			
			if(isset($this->_tree[$node->id]))
				$output .= $this->_renderBranch($this->_tree[$node->id]);
			
			$output .= "</li>\n"; 
		}
		$output .= '</ul>';
		return $output;
	}
	
	
	public function setView($view)
	{
		$this->view = $view;
	}
	
}

?>