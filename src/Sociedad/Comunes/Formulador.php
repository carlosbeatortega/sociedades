<?php
namespace aner\posicionBundle\comunes;

class Formulador
{
    private $target;
    private $operator = '+';
    private $tabla=array();
    public function __toString() {
        return 'Formulador';
    }
    public function __construct($myarray=array()){
        $this->tabla=$myarray;
        $this->andor=$yo;
        
    }
    public function compara(){
        $vuelta=false;
        if($this->andor=='&&'){
            $vuelta=true;
        }
        for($x=1;$x<count($this->tabla);$x++){
            $this->setOperator($this->tabla[$x][1]);
            $this->setTarget($this->tabla[$x][2]);
            $vuelta1=$this->test($this->tabla[$x][0]);
            if($this->andor=='&&'){
                if(!$vuelta1){
                    $vuelta=false;
                    break;
                }
            }else{
                if($vuelta1){
                    $vuelta=true;
                    break;
                }
            }
        }
        return $vuelta;
    }
    
    /**
     * Gets the target value.
     *
     * @return string The target value
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     * Sets the target value.
     *
     * @param string $target The target value
     */
    public function setTarget($target)
    {
        $this->target = $target;
    }

    /**
     * Gets the comparison operator.
     *
     * @return string The operator
     */
    public function getOperator()
    {
        return $this->operator;
    }

    /**
     * Sets the comparison operator.
     *
     * @param string $operator A valid operator
     */
    public function setOperator($operator)
    {
        if (!$operator) {
            $operator = '==';
        }

        if (!in_array($operator, array('>', '<', '>=', '<=', '==','!='))) {
            throw new \InvalidArgumentException(sprintf('Invalid operator "%s".', $operator));
        }

        $this->operator = $operator;
    }

    /**
     * Tests against the target.
     *
     * @param mixed $test A test value
     */
    public function test($test)
    {
        switch ($this->operator) {
            case '>':
                return $test > $this->target;
            case '>=':
                return $test >= $this->target;
            case '<':
                return $test < $this->target;
            case '<=':
                return $test <= $this->target;
            case '!=':
                return $test != $this->target;
        }

        return $test == $this->target;
    }
}
?>
