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

/* auth/login.twig */
class __TwigTemplate_859b8bba764ab88ac62ec5cd636fe1a5 extends Template
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
            'title' => [$this, 'block_title'],
            'body' => [$this, 'block_body'],
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
    public function block_title(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        yield "Logowanie - ";
        yield from $this->yieldParentBlock("title", $context, $blocks);
        yield from [];
    }

    // line 5
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_body(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        // line 6
        yield "<div class=\"min-h-full flex flex-col justify-center py-12 sm:px-6 lg:px-8 bg-gradient-to-br from-primary-50 to-blue-100\">
    <div class=\"sm:mx-auto sm:w-full sm:max-w-md\">
        <div class=\"text-center\">
            <i class=\"fas fa-home text-primary-600 text-6xl mb-4\"></i>
            <h2 class=\"text-3xl font-extrabold text-gray-900\">";
        // line 10
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["app_name"] ?? null), "html", null, true);
        yield "</h2>
            <p class=\"mt-2 text-sm text-gray-600\">Zaloguj się do panelu administracyjnego</p>
        </div>
    </div>

    <div class=\"mt-8 sm:mx-auto sm:w-full sm:max-w-md\">
        <div class=\"bg-white py-8 px-4 shadow-xl rounded-lg sm:px-10 fade-in\">
            ";
        // line 17
        if ((($tmp = ($context["error"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
            // line 18
            yield "                <div class=\"mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-md\">
                    <div class=\"flex items-center\">
                        <i class=\"fas fa-exclamation-circle mr-2\"></i>
                        ";
            // line 21
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["error"] ?? null), "html", null, true);
            yield "
                    </div>
                </div>
            ";
        }
        // line 25
        yield "
            <form class=\"space-y-6\" method=\"POST\" action=\"/login\">
                <input type=\"hidden\" name=\"csrf_token\" value=\"";
        // line 27
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->env->getFunction('csrf_token')->getCallable()(), "html", null, true);
        yield "\">
                
                <div>
                    <label for=\"username\" class=\"block text-sm font-medium text-gray-700\">
                        <i class=\"fas fa-user mr-1\"></i>
                        Nazwa użytkownika
                    </label>
                    <div class=\"mt-1\">
                        <input id=\"username\" name=\"username\" type=\"text\" required 
                               class=\"appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm transition-colors duration-200\"
                               placeholder=\"Wprowadź nazwę użytkownika\">
                    </div>
                </div>

                <div>
                    <label for=\"password\" class=\"block text-sm font-medium text-gray-700\">
                        <i class=\"fas fa-lock mr-1\"></i>
                        Hasło
                    </label>
                    <div class=\"mt-1\">
                        <input id=\"password\" name=\"password\" type=\"password\" required 
                               class=\"appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm transition-colors duration-200\"
                               placeholder=\"Wprowadź hasło\">
                    </div>
                </div>

                <div>
                    <button type=\"submit\" 
                            class=\"group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors duration-200\">
                        <span class=\"absolute left-0 inset-y-0 flex items-center pl-3\">
                            <i class=\"fas fa-sign-in-alt text-primary-500 group-hover:text-primary-400\"></i>
                        </span>
                        Zaloguj się
                    </button>
                </div>
            </form>

            <div class=\"mt-6\">
                <div class=\"relative\">
                    <div class=\"absolute inset-0 flex items-center\">
                        <div class=\"w-full border-t border-gray-300\"></div>
                    </div>
                    <div class=\"relative flex justify-center text-sm\">
                        <span class=\"px-2 bg-white text-gray-500\">Domyślne dane logowania</span>
                    </div>
                </div>
                
                <div class=\"mt-3 bg-gray-50 rounded-md p-3\">
                    <p class=\"text-xs text-gray-600 text-center\">
                        <strong>Login:</strong> admin<br>
                        <strong>Hasło:</strong> admin123
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Generowanie tokenu CSRF
function csrf_token() {
    return '";
        // line 88
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->env->getFunction('csrf_token')->getCallable()(), "html", null, true);
        yield "';
}
</script>
";
        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "auth/login.twig";
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
        return array (  169 => 88,  105 => 27,  101 => 25,  94 => 21,  89 => 18,  87 => 17,  77 => 10,  71 => 6,  64 => 5,  52 => 3,  41 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("{% extends \"layout/base.twig\" %}

{% block title %}Logowanie - {{ parent() }}{% endblock %}

{% block body %}
<div class=\"min-h-full flex flex-col justify-center py-12 sm:px-6 lg:px-8 bg-gradient-to-br from-primary-50 to-blue-100\">
    <div class=\"sm:mx-auto sm:w-full sm:max-w-md\">
        <div class=\"text-center\">
            <i class=\"fas fa-home text-primary-600 text-6xl mb-4\"></i>
            <h2 class=\"text-3xl font-extrabold text-gray-900\">{{ app_name }}</h2>
            <p class=\"mt-2 text-sm text-gray-600\">Zaloguj się do panelu administracyjnego</p>
        </div>
    </div>

    <div class=\"mt-8 sm:mx-auto sm:w-full sm:max-w-md\">
        <div class=\"bg-white py-8 px-4 shadow-xl rounded-lg sm:px-10 fade-in\">
            {% if error %}
                <div class=\"mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-md\">
                    <div class=\"flex items-center\">
                        <i class=\"fas fa-exclamation-circle mr-2\"></i>
                        {{ error }}
                    </div>
                </div>
            {% endif %}

            <form class=\"space-y-6\" method=\"POST\" action=\"/login\">
                <input type=\"hidden\" name=\"csrf_token\" value=\"{{ csrf_token() }}\">
                
                <div>
                    <label for=\"username\" class=\"block text-sm font-medium text-gray-700\">
                        <i class=\"fas fa-user mr-1\"></i>
                        Nazwa użytkownika
                    </label>
                    <div class=\"mt-1\">
                        <input id=\"username\" name=\"username\" type=\"text\" required 
                               class=\"appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm transition-colors duration-200\"
                               placeholder=\"Wprowadź nazwę użytkownika\">
                    </div>
                </div>

                <div>
                    <label for=\"password\" class=\"block text-sm font-medium text-gray-700\">
                        <i class=\"fas fa-lock mr-1\"></i>
                        Hasło
                    </label>
                    <div class=\"mt-1\">
                        <input id=\"password\" name=\"password\" type=\"password\" required 
                               class=\"appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm transition-colors duration-200\"
                               placeholder=\"Wprowadź hasło\">
                    </div>
                </div>

                <div>
                    <button type=\"submit\" 
                            class=\"group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors duration-200\">
                        <span class=\"absolute left-0 inset-y-0 flex items-center pl-3\">
                            <i class=\"fas fa-sign-in-alt text-primary-500 group-hover:text-primary-400\"></i>
                        </span>
                        Zaloguj się
                    </button>
                </div>
            </form>

            <div class=\"mt-6\">
                <div class=\"relative\">
                    <div class=\"absolute inset-0 flex items-center\">
                        <div class=\"w-full border-t border-gray-300\"></div>
                    </div>
                    <div class=\"relative flex justify-center text-sm\">
                        <span class=\"px-2 bg-white text-gray-500\">Domyślne dane logowania</span>
                    </div>
                </div>
                
                <div class=\"mt-3 bg-gray-50 rounded-md p-3\">
                    <p class=\"text-xs text-gray-600 text-center\">
                        <strong>Login:</strong> admin<br>
                        <strong>Hasło:</strong> admin123
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Generowanie tokenu CSRF
function csrf_token() {
    return '{{ csrf_token() }}';
}
</script>
{% endblock %}
", "auth/login.twig", "/Users/andrzej.doroszenko/Sites/flats/templates/auth/login.twig");
    }
}
