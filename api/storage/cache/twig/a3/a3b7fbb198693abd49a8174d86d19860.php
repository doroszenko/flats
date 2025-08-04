<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Extension\CoreExtension;
use Twig\Extension\SandboxExtension;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;
use Twig\TemplateWrapper;

/* layout/app.twig */
class __TwigTemplate_0fe79ce59315c087ec7f5ef0000a522a extends Template
{
    private Source $source;
    /**
     * @var array<string, Template>
     */
    private array $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->blocks = [
            'body' => [$this, 'block_body'],
            'alerts' => [$this, 'block_alerts'],
            'content' => [$this, 'block_content'],
        ];
    }

    protected function doGetParent(array $context): bool|string|Template|TemplateWrapper
    {
        // line 1
        return "layout/base.twig";
    }

    protected function doDisplay(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        $this->parent = $this->load("layout/base.twig", 1);
        yield from $this->parent->unwrap()->yield($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_body(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        // line 4
        yield "<div class=\"min-h-full\">
    <!-- Navigation -->
    <nav class=\"bg-white shadow-sm border-b border-gray-200\">
        <div class=\"mx-auto max-w-7xl px-4 sm:px-6 lg:px-8\">
            <div class=\"flex justify-between h-16\">
                <div class=\"flex\">
                    <!-- Logo -->
                    <div class=\"flex-shrink-0 flex items-center\">
                        <a href=\"/flats\">
                            <i class=\"fas fa-home text-primary-600 text-2xl mr-2\"></i>
                            <span class=\"text-xl font-bold text-gray-900\">";
        // line 14
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["app_name"] ?? null), "html", null, true);
        yield "</span>
                        </a>
                    </div>
                </div>
                
                <!-- Right side -->
                <div class=\"hidden sm:ml-6 sm:flex sm:items-center\">
                    <!-- User menu -->
                    <div class=\"ml-3 relative\">
                        <div class=\"flex items-center space-x-4\">
                            <form method=\"POST\" action=\"/logout\" class=\"inline\">
                                <button type=\"submit\" class=\"bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200\">
                                    <i class=\"fas fa-sign-out-alt mr-1\"></i>
                                    Wyloguj
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                
                <!-- Mobile menu button -->
                <div class=\"sm:hidden flex items-center\">
                    <button type=\"button\" onclick=\"toggleMobileMenu()\" class=\"inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-primary-500\">
                        <i class=\"fas fa-bars\"></i>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Mobile menu -->
        <div class=\"sm:hidden hidden\" id=\"mobile-menu\">
            <div class=\"pt-2 pb-3 space-y-1\">
                <a href=\"/flats\" class=\"";
        // line 46
        if (CoreExtension::inFilter("/flats", CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["app"] ?? null), "request", [], "any", false, false, false, 46), "uri", [], "any", false, false, false, 46), "path", [], "any", false, false, false, 46))) {
            yield "bg-primary-50 border-primary-500 text-primary-700";
        } else {
            yield "border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700";
        }
        yield " block pl-3 pr-4 py-2 border-l-4 text-base font-medium\">
                    <i class=\"fas fa-building mr-2\"></i>
                    Mieszkania
                </a>
            </div>
            <div class=\"pt-4 pb-3 border-t border-gray-200\">
                <div class=\"flex items-center px-4\">
                    <div class=\"flex-shrink-0\">
                        <i class=\"fas fa-user-circle text-gray-400 text-2xl\"></i>
                    </div>
                    <div class=\"ml-3\">
                        <div class=\"text-base font-medium text-gray-800\">Administrator</div>
                    </div>
                </div>
                <div class=\"mt-3 space-y-1\">
                    <form method=\"POST\" action=\"/logout\">
                        <button type=\"submit\" class=\"block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100 w-full text-left\">
                            <i class=\"fas fa-sign-out-alt mr-2\"></i>
                            Wyloguj
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Page content -->
    <main class=\"flex-1\">
        ";
        // line 74
        yield from $this->unwrap()->yieldBlock('alerts', $context, $blocks);
        // line 93
        yield "        
        ";
        // line 94
        yield from $this->unwrap()->yieldBlock('content', $context, $blocks);
        // line 95
        yield "    </main>
</div>
";
        yield from [];
    }

    // line 74
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_alerts(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        // line 75
        yield "            ";
        if ((($tmp = ($context["success"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
            // line 76
            yield "                <div class=\"alert-auto-hide bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-md mx-4 mt-4 fade-in\">
                    <div class=\"flex items-center\">
                        <i class=\"fas fa-check-circle mr-2\"></i>
                        ";
            // line 79
            yield ($context["success"] ?? null);
            yield "
                    </div>
                </div>
            ";
        }
        // line 83
        yield "            
            ";
        // line 84
        if ((($tmp = ($context["error"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
            // line 85
            yield "                <div class=\"alert-auto-hide bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-md mx-4 mt-4 fade-in\">
                    <div class=\"flex items-center\">
                        <i class=\"fas fa-exclamation-circle mr-2\"></i>
                        ";
            // line 88
            yield ($context["error"] ?? null);
            yield "
                    </div>
                </div>
            ";
        }
        // line 92
        yield "        ";
        yield from [];
    }

    // line 94
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_content(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "layout/app.twig";
    }

    /**
     * @codeCoverageIgnore
     */
    public function isTraitable(): bool
    {
        return false;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDebugInfo(): array
    {
        return array (  200 => 94,  195 => 92,  188 => 88,  183 => 85,  181 => 84,  178 => 83,  171 => 79,  166 => 76,  163 => 75,  156 => 74,  149 => 95,  147 => 94,  144 => 93,  142 => 74,  107 => 46,  72 => 14,  60 => 4,  53 => 3,  42 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("{% extends \"layout/base.twig\" %}

{% block body %}
<div class=\"min-h-full\">
    <!-- Navigation -->
    <nav class=\"bg-white shadow-sm border-b border-gray-200\">
        <div class=\"mx-auto max-w-7xl px-4 sm:px-6 lg:px-8\">
            <div class=\"flex justify-between h-16\">
                <div class=\"flex\">
                    <!-- Logo -->
                    <div class=\"flex-shrink-0 flex items-center\">
                        <a href=\"/flats\">
                            <i class=\"fas fa-home text-primary-600 text-2xl mr-2\"></i>
                            <span class=\"text-xl font-bold text-gray-900\">{{ app_name }}</span>
                        </a>
                    </div>
                </div>
                
                <!-- Right side -->
                <div class=\"hidden sm:ml-6 sm:flex sm:items-center\">
                    <!-- User menu -->
                    <div class=\"ml-3 relative\">
                        <div class=\"flex items-center space-x-4\">
                            <form method=\"POST\" action=\"/logout\" class=\"inline\">
                                <button type=\"submit\" class=\"bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200\">
                                    <i class=\"fas fa-sign-out-alt mr-1\"></i>
                                    Wyloguj
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                
                <!-- Mobile menu button -->
                <div class=\"sm:hidden flex items-center\">
                    <button type=\"button\" onclick=\"toggleMobileMenu()\" class=\"inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-primary-500\">
                        <i class=\"fas fa-bars\"></i>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Mobile menu -->
        <div class=\"sm:hidden hidden\" id=\"mobile-menu\">
            <div class=\"pt-2 pb-3 space-y-1\">
                <a href=\"/flats\" class=\"{% if '/flats' in app.request.uri.path %}bg-primary-50 border-primary-500 text-primary-700{% else %}border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700{% endif %} block pl-3 pr-4 py-2 border-l-4 text-base font-medium\">
                    <i class=\"fas fa-building mr-2\"></i>
                    Mieszkania
                </a>
            </div>
            <div class=\"pt-4 pb-3 border-t border-gray-200\">
                <div class=\"flex items-center px-4\">
                    <div class=\"flex-shrink-0\">
                        <i class=\"fas fa-user-circle text-gray-400 text-2xl\"></i>
                    </div>
                    <div class=\"ml-3\">
                        <div class=\"text-base font-medium text-gray-800\">Administrator</div>
                    </div>
                </div>
                <div class=\"mt-3 space-y-1\">
                    <form method=\"POST\" action=\"/logout\">
                        <button type=\"submit\" class=\"block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100 w-full text-left\">
                            <i class=\"fas fa-sign-out-alt mr-2\"></i>
                            Wyloguj
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Page content -->
    <main class=\"flex-1\">
        {% block alerts %}
            {% if success %}
                <div class=\"alert-auto-hide bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-md mx-4 mt-4 fade-in\">
                    <div class=\"flex items-center\">
                        <i class=\"fas fa-check-circle mr-2\"></i>
                        {{ success|raw }}
                    </div>
                </div>
            {% endif %}
            
            {% if error %}
                <div class=\"alert-auto-hide bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-md mx-4 mt-4 fade-in\">
                    <div class=\"flex items-center\">
                        <i class=\"fas fa-exclamation-circle mr-2\"></i>
                        {{ error|raw }}
                    </div>
                </div>
            {% endif %}
        {% endblock %}
        
        {% block content %}{% endblock %}
    </main>
</div>
{% endblock %}
", "layout/app.twig", "/Users/andrzej.doroszenko/Sites/flats/templates/layout/app.twig");
    }
}
