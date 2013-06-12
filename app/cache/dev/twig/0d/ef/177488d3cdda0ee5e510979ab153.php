<?php

/* WebProfilerBundle:Profiler:toolbar_js.html.twig */
class __TwigTemplate_0def177488d3cdda0ee5e510979ab153 extends Twig_Template
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
        echo "<div id=\"sfwdt";
        echo twig_escape_filter($this->env, $this->getContext($context, "token"), "html", null, true);
        echo "\" class=\"sf-toolbar\" style=\"display: none\"></div>
";
        // line 2
        $this->env->loadTemplate("WebProfilerBundle:Profiler:base_js.html.twig")->display($context);
        // line 3
        echo "<script type=\"text/javascript\">/*<![CDATA[*/
    (function () {
        ";
        // line 5
        if (("top" == $this->getContext($context, "position"))) {
            // line 6
            echo "            var sfwdt = document.getElementById('sfwdt";
            echo twig_escape_filter($this->env, $this->getContext($context, "token"), "html", null, true);
            echo "');
            document.body.insertBefore(
                document.body.removeChild(sfwdt),
                document.body.firstChild
            );
        ";
        }
        // line 12
        echo "
        Sfjs.load(
            'sfwdt";
        // line 14
        echo twig_escape_filter($this->env, $this->getContext($context, "token"), "html", null, true);
        echo "',
            '";
        // line 15
        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("_wdt", array("token" => $this->getContext($context, "token"))), "html", null, true);
        echo "',
            function(xhr, el) {
                el.style.display = -1 !== xhr.responseText.indexOf('sf-toolbarreset') ? 'block' : 'none';
            },
            function(xhr) {
                if (xhr.status !== 0) {
                    confirm('An error occurred while loading the web debug toolbar (' + xhr.status + ': ' + xhr.statusText + ').\\n\\nDo you want to open the profiler?') && (window.location = '";
        // line 21
        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("_profiler", array("token" => $this->getContext($context, "token"))), "html", null, true);
        echo "');
                }
            }
        );
    })();
/*]]>*/</script>
";
    }

    public function getTemplateName()
    {
        return "WebProfilerBundle:Profiler:toolbar_js.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  30 => 5,  84 => 19,  74 => 16,  66 => 15,  36 => 7,  25 => 4,  105 => 24,  98 => 22,  96 => 21,  93 => 20,  89 => 19,  76 => 16,  68 => 12,  50 => 15,  27 => 4,  24 => 2,  22 => 2,  225 => 96,  216 => 90,  212 => 88,  205 => 84,  201 => 83,  196 => 80,  194 => 79,  191 => 78,  189 => 77,  186 => 76,  180 => 72,  172 => 67,  163 => 63,  159 => 61,  157 => 60,  154 => 59,  147 => 55,  143 => 54,  138 => 51,  136 => 50,  132 => 48,  130 => 47,  127 => 46,  121 => 45,  118 => 44,  114 => 43,  100 => 34,  78 => 28,  71 => 26,  63 => 24,  58 => 9,  34 => 11,  19 => 1,  94 => 39,  88 => 6,  81 => 40,  59 => 21,  48 => 19,  26 => 3,  21 => 2,  43 => 7,  32 => 6,  29 => 3,  202 => 62,  199 => 61,  195 => 59,  192 => 58,  188 => 48,  185 => 47,  181 => 27,  178 => 71,  169 => 49,  167 => 47,  164 => 46,  155 => 43,  150 => 42,  146 => 41,  142 => 39,  134 => 37,  128 => 34,  124 => 33,  119 => 32,  117 => 31,  112 => 28,  110 => 26,  104 => 36,  101 => 21,  95 => 31,  91 => 20,  87 => 16,  83 => 18,  79 => 17,  75 => 27,  70 => 12,  61 => 9,  57 => 14,  53 => 7,  49 => 6,  44 => 10,  41 => 9,  35 => 4,  72 => 14,  67 => 11,  60 => 23,  55 => 13,  51 => 12,  46 => 14,  42 => 12,  39 => 6,  33 => 5,  31 => 5,  28 => 3,);
    }
}
