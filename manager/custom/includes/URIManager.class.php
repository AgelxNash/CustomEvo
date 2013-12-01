<?php
class URIManager{
	protected $modx = null;
	protected $contentTable = null;
	protected $total = 0;
	
	public function __construct(DocumentParser $modx){
		$this->_modx = $modx;
		$this->contentTable = $this->_modx->getFullTableName("site_content");
	}
	public function update($id){
		$this->total = 0;
		$q = $this->_modx->db->query("SELECT id, alias, parent, isfolder, template FROM ".$this->contentTable." WHERE id = ".$id);
		if($this->_modx->db->getRecordCount($q)){
			$data = $this->_modx->db->getRow($q);			
			$uri = $this->_modx->makeUrl($data['parent']).$data['alias']."/";
			if($data['isfolder']){
				$this->_update($id, $uri);
			}
		}
		return $this->total;
	}
	protected function _update($ID, $URI){
		$q = $this->_modx->db->query("SELECT id,alias,uri,isfolder,template FROM ".$this->contentTable." WHERE parent = ".$ID);
		while($data = $this->_modx->db->getRow($q)){
			$nextURI = $URI.$data['alias'] . ($data['isfolder'] ? "/" : $this->_modx->config['friendly_url_suffix']);
			if($nextURI != $data['uri']){
				$flag = $this->_modx->db->query("UPDATE ".$this->contentTable." SET uri = '".$this->_modx->db->escape($nextURI)."' WHERE id = ".$data['id']);
				if($flag){
					$this->total++;
				}
			}
			if($data['isfolder']){
				$this->_update($data['id'], $nextURI);
			}
		}
	}
}