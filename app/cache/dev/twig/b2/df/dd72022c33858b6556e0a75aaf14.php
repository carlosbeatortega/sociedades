<?php

/* TwigBundle:Exception:logs.html.twig */
class __TwigTemplate_b2dfdd72022c33858b6556e0a75aaf14 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<ol class=\"traces logs\">
    ";
        // line 2
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable($this->getContext($context, "logs"));
        foreach ($context['_seq'] as $context["_key"] => $context["log"]) {
            // line 3
            echo "        <li";
            if (($this->getAttribute($this->getContext($context, "log"), "priority") >= 400)) {
                echo " class=\"error\"";
            } elseif (($this->getAttribute($this->getContext($context, "log"), "priority") >= 300)) {
                echo " class=\"warning\"";
            }
            echo ">
            ";
            // line 4
            echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, "log"), "priorityName"), "html", null, true);
            echo " - ";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, "log"), "message"), "html", null, true);
            echo "
        </li>
    ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['log'], $context['_parent'], $context['loop']);
        $context = array_merge($_parent, array_intersect_key($context, $_parent));
        // line 7
        echo "</ol>
";
    }

    public function getTemplateName()
    {
        return "TwigBundle:Exception:logs.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  84 => 19,  74 => 16,  66 => 15,  36 => 7,  25 => 4,  105 => 24,  98 => 22,  96 => 21,  93 => 20,  89 => 19,  76 => 16,  68 => 12,  50 => 8,  27 => 4,  24 => 3,  22 => 2,  225 => 96,  216 => 90,  212 => 88,  205 => 84,  201 => 83,  196 => 80,  194 => 79,  191 => 78,  189 => 77,  186 => 76,  180 => 72,  172 => 67,  163 => 63,  159 => 61,  157 => 60,  154 => 59,  147 => 55,  143 => 54,  138 => 51,  136 => 50,  132 => 48,  130 => 47,  127 => 46,  121 => 45,  118 => 44,  114 => 43,  100 => 34,  78 => 28,  71 => 26,  63 => 24,  58 => 9,  34 => 11,  19 => 1,  94 => 39,  88 => 6,  81 => 40,  59 => 22,  48 => 19,  26 => 3,  21 => 2,  43 => 7,  32 => 4,  29 => 3,  202 => 62,  199 => 61,  195 => 59,  192 => 58,  188 => 48,  185 => 47,  181 => 27,  178 => 71,  169 => 49,  167 => 47,  164 => 46,  155 => 43,  150 => 42,  146 => 41,  142 => 39,  134 => 37,  128 => 34,  124 => 33,  119 => 32,  117 => 31,  112 => 28,  110 => 26,  104 => 36,  101 => 21,  95 => 31,  91 => 20,  87 => 16,  83 => 18,  79 => 17,  75 => 27,  70 => 12,  61 => 9,  57 => 14,  53 => 7,  49 => 6,  44 => 10,  41 => 9,  35 => 4,  72 => 14,  67 => 11,  60 => 23,  55 => 13,  51 => 12,  46 => 7,  42 => 8,  39 => 6,  33 => 5,  31 => 5,  28 => 3,);
    }
}
