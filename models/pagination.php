<?php
class Pagination {
    public $limit;
    public $offset;
    public $maximum;
    public $get_parameter;

    public function __construct($limit, $offset, $maximum) {
        $this->limit        = $limit;
        $this->offset       = $offset;
        $this->maximum      = $maximum;
        $this->get_parameter = $_GET;
    }

    public function getPaginationHtml() {
        $html = '<nav><ul class="pagination">';
        // $html .= $this->limit.','.$this->offset.','.$this->maximum.',';
        if ($this->maximum >1) {
            if ($this->offset>0)
                $html .= '<li class="page-item"><a class="btn btn-default" href="'.$this->getLink($this->offset-$this->limit).'">Prev</a></li>';
            // $html .= $this->getLink(0);
            for ($count=0, $page=1; $count<$this->maximum; $count+=$this->limit, $page++) {
                if ($page==1 
                || $count+$this->limit>=$this->maximum 
                || ($count>=$this->offset-3*$this->limit
                && $count<=$this->offset+3*$this->limit)) {
                    if ($count==$this->offset)
                        $html .= '<li class="page-item active"><a class="btn btn-default" href="'.$this->getLink($count).'">'.$page.'</a></li>';
                    else
                        $html .= '<li class="page-item"><a class="btn btn-default" href="'.$this->getLink($count).'">'.$page.'</a></li>';
                }
            }
            if ($this->offset+$this->limit<$this->maximum)
                $html .= '<li class="page-item"><a class="btn btn-default" href="'.$this->getLink($this->offset+$this->limit).'">Next</a></li>';

            $html .= '</ul></nav>';
        }
        return $html;
    }

    public function getLink($new_offset) {
        $new_get_parameter = $this->get_parameter;
        $new_get_parameter['offset'] = $new_offset;

        return '?'.http_build_query($new_get_parameter);
    }

    public function getLimitLink($new_limit) {
        $new_get_parameter = $this->get_parameter;
        $new_get_parameter['limit'] = $new_limit;

        return '?'.http_build_query($new_get_parameter);
    }
}
?>