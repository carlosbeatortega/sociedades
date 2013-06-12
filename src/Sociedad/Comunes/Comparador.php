<?php
namespace aner\posicionBundle\comunes;

class Comparador
{
    private $target;
    private $operator = '==';
    private $andor='&&';
    private $tabla=array();
    private $concatena=array();
    public function __toString() {
        return 'Comparador';
    }
    public function __construct($myarray=array(),$yo='&&'){
        $this->tabla=$myarray;
        $this->andor=$yo;
        
    }
    public function compara(){
        $vuelta=false;
        if($this->andor=='&&'){
            $vuelta=true;
        }
        for($x=0;$x<count($this->tabla);$x++){
            $this->setOperator($this->tabla[$x][1]);
            $this->setTarget($this->tabla[$x][2]);
            $vuelta1=$this->test($this->tabla[$x][0]);
//            if($this->concatena[$x]){
//                
//            }
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
     * Gets the comparison concatena.
     *
     * @return string The concatena
     */
    public function getConcatena()
    {
        return $this->concatena;
    }

    /**
     * Sets the comparison concatena.
     *
     * @param string $concatena A valid operator
     */
    public function setConcatena($concatena,$x=0)
    {
        if (!$concatena) {
            $this->concatena[$x] = '&&';
        }

        if (!in_array($concatena, array('&&', '||'))) {
            throw new \InvalidArgumentException(sprintf('Invalid operator "%s".', $concatena));
        }

        $this->concatena[$x] = $concatena;
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
