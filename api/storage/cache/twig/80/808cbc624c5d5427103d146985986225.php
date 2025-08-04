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

/* flats/index.twig */
class __TwigTemplate_df74c125a386227677f799f5a6a2c7b8 extends Template
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
            'content' => [$this, 'block_content'],
        ];
    }

    protected function doGetParent(array $context): bool|string|Template|TemplateWrapper
    {
        // line 1
        return "layout/app.twig";
    }

    protected function doDisplay(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        $this->parent = $this->load("layout/app.twig", 1);
        yield from $this->parent->unwrap()->yield($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_title(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        yield "Mieszkania - ";
        yield from $this->yieldParentBlock("title", $context, $blocks);
        yield from [];
    }

    // line 5
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_content(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        // line 6
        yield "<div class=\"py-6\">
    <div class=\"max-w-7xl mx-auto px-4 sm:px-6 md:px-8\">
        <!-- Page header -->
        <div class=\"md:flex md:items-center md:justify-between\">
            <div class=\"mt-4 flex md:mt-0 md:ml-4\">
                <a href=\"/flats/create\" class=\"inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors duration-200\">
                    <i class=\"fas fa-plus mr-2\"></i>
                    Dodaj mieszkanie
                </a>
            </div>
        </div>

        <!-- Flats grid -->
        <div class=\"mt-8\">
            ";
        // line 20
        if (Twig\Extension\CoreExtension::testEmpty(($context["flats"] ?? null))) {
            // line 21
            yield "                <div class=\"text-center py-12 bg-white rounded-lg shadow\">
                    <i class=\"fas fa-building text-gray-300 text-6xl mb-4\"></i>
                    <h3 class=\"text-lg font-medium text-gray-900 mb-2\">Brak mieszkań</h3>
                    <p class=\"text-gray-500 mb-6\">Rozpocznij od dodania pierwszego mieszkania</p>
                    <a href=\"/flats/create\" class=\"inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-primary-600 bg-primary-100 hover:bg-primary-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors duration-200\">
                        <i class=\"fas fa-plus mr-2\"></i>
                        Dodaj mieszkanie
                    </a>
                </div>
            ";
        } else {
            // line 31
            yield "                <div class=\"grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3\">
                    ";
            // line 32
            $context['_parent'] = $context;
            $context['_seq'] = CoreExtension::ensureTraversable(($context["flats"] ?? null));
            foreach ($context['_seq'] as $context["_key"] => $context["flat"]) {
                // line 33
                yield "                        <div class=\"bg-white overflow-hidden shadow rounded-lg hover:shadow-lg transition-shadow duration-200 fade-in\">
                            <div class=\"px-4 py-5 sm:p-6\">
                                <div class=\"flex items-center justify-between mb-4\">
                                    <div class=\"flex items-center space-x-3\">
                                        <div class=\"flex-shrink-0\">
                                            <i class=\"fas fa-home text-primary-600 text-xl\"></i>
                                        </div>
                                        <div>
                                            <h3 class=\"text-lg font-medium text-gray-900\">";
                // line 41
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["flat"], "name", [], "any", false, false, false, 41), "html", null, true);
                yield "</h3>
                                        </div>
                                    </div>
                                    <div class=\"flex-shrink-0\">
                                        <div class=\"relative inline-block text-left\">
                                            <button type=\"button\" class=\"bg-white rounded-full flex items-center text-gray-400 hover:text-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500\" 
                                                    onclick=\"toggleDropdown('dropdown-";
                // line 47
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["flat"], "id", [], "any", false, false, false, 47), "html", null, true);
                yield "')\">
                                                <i class=\"fas fa-ellipsis-v\"></i>
                                            </button>
                                            <div class=\"origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 hidden z-10\" 
                                                 id=\"dropdown-";
                // line 51
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["flat"], "id", [], "any", false, false, false, 51), "html", null, true);
                yield "\">
                                                <div class=\"py-1\">
                                                    <a href=\"/flats/";
                // line 53
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["flat"], "id", [], "any", false, false, false, 53), "html", null, true);
                yield "\" class=\"block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100\">
                                                        <i class=\"fas fa-eye mr-2\"></i>
                                                        Zobacz
                                                    </a>
                                                    <a href=\"/flats/";
                // line 57
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["flat"], "id", [], "any", false, false, false, 57), "html", null, true);
                yield "/edit\" class=\"block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100\">
                                                        <i class=\"fas fa-edit mr-2\"></i>
                                                        Edytuj
                                                    </a>
                                                    <a href=\"/flats/";
                // line 61
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["flat"], "id", [], "any", false, false, false, 61), "html", null, true);
                yield "/bills\" class=\"block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100\">
                                                        <i class=\"fas fa-file-invoice mr-2\"></i>
                                                        Rozliczenia
                                                    </a>
                                                    <a href=\"/flats/";
                // line 65
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["flat"], "id", [], "any", false, false, false, 65), "html", null, true);
                yield "/history\" class=\"block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100\">
                                                        <i class=\"fas fa-chart-line mr-2\"></i>
                                                        Historia
                                                    </a>
                                                    <div class=\"border-t border-gray-100\"></div>
                                                    <form method=\"POST\" action=\"/flats/";
                // line 70
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["flat"], "id", [], "any", false, false, false, 70), "html", null, true);
                yield "/delete\" class=\"block\">
                                                        <input type=\"hidden\" name=\"csrf_token\" value=\"";
                // line 71
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->env->getFunction('csrf_token')->getCallable()(), "html", null, true);
                yield "\">
                                                        <button type=\"submit\" onclick=\"return confirmDelete('Czy na pewno chcesz usunąć mieszkanie ";
                // line 72
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["flat"], "name", [], "any", false, false, false, 72), "html", null, true);
                yield "? Wszystkie rozliczenia zostaną usunięte.')\" 
                                                                class=\"block w-full text-left px-4 py-2 text-sm text-red-700 hover:bg-red-50\">
                                                            <i class=\"fas fa-trash mr-2\"></i>
                                                            Usuń
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Utilities -->
                                <div class=\"mb-4\">
                                    <h4 class=\"text-sm font-medium text-gray-700 mb-2\">Liczniki:</h4>
                                    ";
                // line 87
                if (Twig\Extension\CoreExtension::testEmpty(CoreExtension::getAttribute($this->env, $this->source, $context["flat"], "utilities", [], "any", false, false, false, 87))) {
                    // line 88
                    yield "                                        <p class=\"text-sm text-gray-500\">Brak skonfigurowanych liczników</p>
                                    ";
                } else {
                    // line 90
                    yield "                                        <div class=\"flex flex-wrap gap-1\">
                                            ";
                    // line 91
                    $context['_parent'] = $context;
                    $context['_seq'] = CoreExtension::ensureTraversable(CoreExtension::getAttribute($this->env, $this->source, $context["flat"], "utilities", [], "any", false, false, false, 91));
                    foreach ($context['_seq'] as $context["utilityId"] => $context["utility"]) {
                        // line 92
                        yield "                                                <span class=\"inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800\">
                                                    ";
                        // line 93
                        if ((CoreExtension::getAttribute($this->env, $this->source, $context["utility"], "type", [], "any", false, false, false, 93) == "gas")) {
                            // line 94
                            yield "                                                        <i class=\"fas fa-fire mr-1\"></i>Gaz
                                                    ";
                        } elseif ((CoreExtension::getAttribute($this->env, $this->source,                         // line 95
$context["utility"], "type", [], "any", false, false, false, 95) == "electricity")) {
                            // line 96
                            yield "                                                        <i class=\"fas fa-bolt mr-1\"></i>Prąd
                                                    ";
                        } elseif ((CoreExtension::getAttribute($this->env, $this->source,                         // line 97
$context["utility"], "type", [], "any", false, false, false, 97) == "cold_water")) {
                            // line 98
                            yield "                                                        <i class=\"fas fa-tint mr-1\"></i>Woda zimna
                                                    ";
                        } elseif ((CoreExtension::getAttribute($this->env, $this->source,                         // line 99
$context["utility"], "type", [], "any", false, false, false, 99) == "hot_water")) {
                            // line 100
                            yield "                                                        <i class=\"fas fa-tint mr-1 text-red-500\"></i>Woda ciepła

                                                    ";
                        } else {
                            // line 103
                            yield "                                                        ";
                            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::titleCase($this->env->getCharset(), CoreExtension::getAttribute($this->env, $this->source, $context["utility"], "type", [], "any", false, false, false, 103)), "html", null, true);
                            yield "
                                                    ";
                        }
                        // line 105
                        yield "                                                    ";
                        if ((($tmp = CoreExtension::getAttribute($this->env, $this->source, $context["utility"], "name", [], "any", false, false, false, 105)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                            // line 106
                            yield "                                                        / ";
                            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["utility"], "name", [], "any", false, false, false, 106), "html", null, true);
                            yield "
                                                    ";
                        }
                        // line 108
                        yield "                                                </span>
                                            ";
                    }
                    $_parent = $context['_parent'];
                    unset($context['_seq'], $context['utilityId'], $context['utility'], $context['_parent']);
                    $context = array_intersect_key($context, $_parent) + $_parent;
                    // line 110
                    yield "                                        </div>
                                    ";
                }
                // line 112
                yield "                                </div>

                                <!-- Stats -->
                                <div class=\"grid grid-cols-2 gap-4 text-center\">
                                    <div>
                                        <div class=\"text-2xl font-bold text-gray-900\">";
                // line 117
                yield (((CoreExtension::getAttribute($this->env, $this->source, $context["flat"], "bills_count", [], "any", true, true, false, 117) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, $context["flat"], "bills_count", [], "any", false, false, false, 117)))) ? ($this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["flat"], "bills_count", [], "any", false, false, false, 117), "html", null, true)) : (0));
                yield "</div>
                                        <div class=\"text-xs text-gray-500\">Rozliczeń</div>
                                    </div>
                                    <div>
                                        <div class=\"text-2xl font-bold ";
                // line 121
                if (((((CoreExtension::getAttribute($this->env, $this->source, $context["flat"], "pending_bills", [], "any", true, true, false, 121) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, $context["flat"], "pending_bills", [], "any", false, false, false, 121)))) ? (CoreExtension::getAttribute($this->env, $this->source, $context["flat"], "pending_bills", [], "any", false, false, false, 121)) : (0)) > 0)) {
                    yield "text-yellow-600";
                } else {
                    yield "text-green-600";
                }
                yield "\">
                                            ";
                // line 122
                yield (((CoreExtension::getAttribute($this->env, $this->source, $context["flat"], "pending_bills", [], "any", true, true, false, 122) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, $context["flat"], "pending_bills", [], "any", false, false, false, 122)))) ? ($this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["flat"], "pending_bills", [], "any", false, false, false, 122), "html", null, true)) : (0));
                yield "
                                        </div>
                                        <div class=\"text-xs text-gray-500\">Oczekujących</div>
                                    </div>
                                </div>

                                <!-- Last bill -->
                                ";
                // line 129
                if ((($tmp = CoreExtension::getAttribute($this->env, $this->source, $context["flat"], "last_bill", [], "any", false, false, false, 129)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                    // line 130
                    yield "                                    <div class=\"mt-4 pt-4 border-t border-gray-200\">
                                        <div class=\"flex items-center justify-between text-sm\">
                                            <span class=\"text-gray-500\">Ostatnie rozliczenie:</span>
                                            <span class=\"font-medium\">";
                    // line 133
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, $context["flat"], "last_bill", [], "any", false, false, false, 133), "period", [], "any", false, false, false, 133), "html", null, true);
                    yield "</span>
                                        </div>
                                        <div class=\"flex items-center justify-between text-sm mt-1\">
                                            <span class=\"text-gray-500\">Koszt:</span>
                                            <span class=\"font-medium\">";
                    // line 137
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Twig\Extension\CoreExtension']->formatNumber(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, $context["flat"], "last_bill", [], "any", false, false, false, 137), "total_cost", [], "any", false, false, false, 137), 2, ",", " "), "html", null, true);
                    yield " zł</span>
                                        </div>
                                    </div>
                                ";
                }
                // line 141
                yield "
                                <!-- Actions -->
                                <div class=\"mt-4 pt-4 border-t border-gray-200 flex space-x-2\">
                                    <a href=\"/flats/";
                // line 144
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["flat"], "id", [], "any", false, false, false, 144), "html", null, true);
                yield "\" class=\"flex-1 bg-primary-50 text-primary-700 hover:bg-primary-100 px-3 py-2 rounded-md text-sm font-medium text-center transition-colors duration-200\">
                                        <i class=\"fas fa-eye mr-1\"></i>
                                        Zobacz
                                    </a>
                                    <a href=\"/flats/";
                // line 148
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["flat"], "id", [], "any", false, false, false, 148), "html", null, true);
                yield "/bills/create\" class=\"flex-1 bg-green-50 text-green-700 hover:bg-green-100 px-3 py-2 rounded-md text-sm font-medium text-center transition-colors duration-200\">
                                        <i class=\"fas fa-plus mr-1\"></i>
                                        Rozliczenie
                                    </a>
                                </div>
                            </div>
                        </div>
                    ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_key'], $context['flat'], $context['_parent']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 156
            yield "                </div>
            ";
        }
        // line 158
        yield "        </div>
    </div>
</div>

<script>
function toggleDropdown(id) {
    const dropdown = document.getElementById(id);
    const allDropdowns = document.querySelectorAll('[id^=\"dropdown-\"]');
    
    // Zamknij wszystkie inne dropdowny
    allDropdowns.forEach(d => {
        if (d.id !== id) {
            d.classList.add('hidden');
        }
    });
    
    // Toggle bieżący dropdown
    dropdown.classList.toggle('hidden');
}

// Zamknij dropdowny po kliknięciu poza nimi
document.addEventListener('click', function(event) {
    if (!event.target.closest('[onclick*=\"toggleDropdown\"]')) {
        const allDropdowns = document.querySelectorAll('[id^=\"dropdown-\"]');
        allDropdowns.forEach(d => d.classList.add('hidden'));
    }
});
</script>
";
        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "flats/index.twig";
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
        return array (  341 => 158,  337 => 156,  323 => 148,  316 => 144,  311 => 141,  304 => 137,  297 => 133,  292 => 130,  290 => 129,  280 => 122,  272 => 121,  265 => 117,  258 => 112,  254 => 110,  247 => 108,  241 => 106,  238 => 105,  232 => 103,  227 => 100,  225 => 99,  222 => 98,  220 => 97,  217 => 96,  215 => 95,  212 => 94,  210 => 93,  207 => 92,  203 => 91,  200 => 90,  196 => 88,  194 => 87,  176 => 72,  172 => 71,  168 => 70,  160 => 65,  153 => 61,  146 => 57,  139 => 53,  134 => 51,  127 => 47,  118 => 41,  108 => 33,  104 => 32,  101 => 31,  89 => 21,  87 => 20,  71 => 6,  64 => 5,  52 => 3,  41 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("{% extends \"layout/app.twig\" %}

{% block title %}Mieszkania - {{ parent() }}{% endblock %}

{% block content %}
<div class=\"py-6\">
    <div class=\"max-w-7xl mx-auto px-4 sm:px-6 md:px-8\">
        <!-- Page header -->
        <div class=\"md:flex md:items-center md:justify-between\">
            <div class=\"mt-4 flex md:mt-0 md:ml-4\">
                <a href=\"/flats/create\" class=\"inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors duration-200\">
                    <i class=\"fas fa-plus mr-2\"></i>
                    Dodaj mieszkanie
                </a>
            </div>
        </div>

        <!-- Flats grid -->
        <div class=\"mt-8\">
            {% if flats is empty %}
                <div class=\"text-center py-12 bg-white rounded-lg shadow\">
                    <i class=\"fas fa-building text-gray-300 text-6xl mb-4\"></i>
                    <h3 class=\"text-lg font-medium text-gray-900 mb-2\">Brak mieszkań</h3>
                    <p class=\"text-gray-500 mb-6\">Rozpocznij od dodania pierwszego mieszkania</p>
                    <a href=\"/flats/create\" class=\"inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-primary-600 bg-primary-100 hover:bg-primary-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors duration-200\">
                        <i class=\"fas fa-plus mr-2\"></i>
                        Dodaj mieszkanie
                    </a>
                </div>
            {% else %}
                <div class=\"grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3\">
                    {% for flat in flats %}
                        <div class=\"bg-white overflow-hidden shadow rounded-lg hover:shadow-lg transition-shadow duration-200 fade-in\">
                            <div class=\"px-4 py-5 sm:p-6\">
                                <div class=\"flex items-center justify-between mb-4\">
                                    <div class=\"flex items-center space-x-3\">
                                        <div class=\"flex-shrink-0\">
                                            <i class=\"fas fa-home text-primary-600 text-xl\"></i>
                                        </div>
                                        <div>
                                            <h3 class=\"text-lg font-medium text-gray-900\">{{ flat.name }}</h3>
                                        </div>
                                    </div>
                                    <div class=\"flex-shrink-0\">
                                        <div class=\"relative inline-block text-left\">
                                            <button type=\"button\" class=\"bg-white rounded-full flex items-center text-gray-400 hover:text-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500\" 
                                                    onclick=\"toggleDropdown('dropdown-{{ flat.id }}')\">
                                                <i class=\"fas fa-ellipsis-v\"></i>
                                            </button>
                                            <div class=\"origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 hidden z-10\" 
                                                 id=\"dropdown-{{ flat.id }}\">
                                                <div class=\"py-1\">
                                                    <a href=\"/flats/{{ flat.id }}\" class=\"block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100\">
                                                        <i class=\"fas fa-eye mr-2\"></i>
                                                        Zobacz
                                                    </a>
                                                    <a href=\"/flats/{{ flat.id }}/edit\" class=\"block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100\">
                                                        <i class=\"fas fa-edit mr-2\"></i>
                                                        Edytuj
                                                    </a>
                                                    <a href=\"/flats/{{ flat.id }}/bills\" class=\"block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100\">
                                                        <i class=\"fas fa-file-invoice mr-2\"></i>
                                                        Rozliczenia
                                                    </a>
                                                    <a href=\"/flats/{{ flat.id }}/history\" class=\"block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100\">
                                                        <i class=\"fas fa-chart-line mr-2\"></i>
                                                        Historia
                                                    </a>
                                                    <div class=\"border-t border-gray-100\"></div>
                                                    <form method=\"POST\" action=\"/flats/{{ flat.id }}/delete\" class=\"block\">
                                                        <input type=\"hidden\" name=\"csrf_token\" value=\"{{ csrf_token() }}\">
                                                        <button type=\"submit\" onclick=\"return confirmDelete('Czy na pewno chcesz usunąć mieszkanie {{ flat.name }}? Wszystkie rozliczenia zostaną usunięte.')\" 
                                                                class=\"block w-full text-left px-4 py-2 text-sm text-red-700 hover:bg-red-50\">
                                                            <i class=\"fas fa-trash mr-2\"></i>
                                                            Usuń
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Utilities -->
                                <div class=\"mb-4\">
                                    <h4 class=\"text-sm font-medium text-gray-700 mb-2\">Liczniki:</h4>
                                    {% if flat.utilities is empty %}
                                        <p class=\"text-sm text-gray-500\">Brak skonfigurowanych liczników</p>
                                    {% else %}
                                        <div class=\"flex flex-wrap gap-1\">
                                            {% for utilityId, utility in flat.utilities %}
                                                <span class=\"inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800\">
                                                    {% if utility.type == 'gas' %}
                                                        <i class=\"fas fa-fire mr-1\"></i>Gaz
                                                    {% elseif utility.type == 'electricity' %}
                                                        <i class=\"fas fa-bolt mr-1\"></i>Prąd
                                                    {% elseif utility.type == 'cold_water' %}
                                                        <i class=\"fas fa-tint mr-1\"></i>Woda zimna
                                                    {% elseif utility.type == 'hot_water' %}
                                                        <i class=\"fas fa-tint mr-1 text-red-500\"></i>Woda ciepła

                                                    {% else %}
                                                        {{ utility.type|title }}
                                                    {% endif %}
                                                    {% if utility.name %}
                                                        / {{ utility.name }}
                                                    {% endif %}
                                                </span>
                                            {% endfor %}
                                        </div>
                                    {% endif %}
                                </div>

                                <!-- Stats -->
                                <div class=\"grid grid-cols-2 gap-4 text-center\">
                                    <div>
                                        <div class=\"text-2xl font-bold text-gray-900\">{{ flat.bills_count ?? 0 }}</div>
                                        <div class=\"text-xs text-gray-500\">Rozliczeń</div>
                                    </div>
                                    <div>
                                        <div class=\"text-2xl font-bold {% if (flat.pending_bills ?? 0) > 0 %}text-yellow-600{% else %}text-green-600{% endif %}\">
                                            {{ flat.pending_bills ?? 0 }}
                                        </div>
                                        <div class=\"text-xs text-gray-500\">Oczekujących</div>
                                    </div>
                                </div>

                                <!-- Last bill -->
                                {% if flat.last_bill %}
                                    <div class=\"mt-4 pt-4 border-t border-gray-200\">
                                        <div class=\"flex items-center justify-between text-sm\">
                                            <span class=\"text-gray-500\">Ostatnie rozliczenie:</span>
                                            <span class=\"font-medium\">{{ flat.last_bill.period }}</span>
                                        </div>
                                        <div class=\"flex items-center justify-between text-sm mt-1\">
                                            <span class=\"text-gray-500\">Koszt:</span>
                                            <span class=\"font-medium\">{{ flat.last_bill.total_cost|number_format(2, ',', ' ') }} zł</span>
                                        </div>
                                    </div>
                                {% endif %}

                                <!-- Actions -->
                                <div class=\"mt-4 pt-4 border-t border-gray-200 flex space-x-2\">
                                    <a href=\"/flats/{{ flat.id }}\" class=\"flex-1 bg-primary-50 text-primary-700 hover:bg-primary-100 px-3 py-2 rounded-md text-sm font-medium text-center transition-colors duration-200\">
                                        <i class=\"fas fa-eye mr-1\"></i>
                                        Zobacz
                                    </a>
                                    <a href=\"/flats/{{ flat.id }}/bills/create\" class=\"flex-1 bg-green-50 text-green-700 hover:bg-green-100 px-3 py-2 rounded-md text-sm font-medium text-center transition-colors duration-200\">
                                        <i class=\"fas fa-plus mr-1\"></i>
                                        Rozliczenie
                                    </a>
                                </div>
                            </div>
                        </div>
                    {% endfor %}
                </div>
            {% endif %}
        </div>
    </div>
</div>

<script>
function toggleDropdown(id) {
    const dropdown = document.getElementById(id);
    const allDropdowns = document.querySelectorAll('[id^=\"dropdown-\"]');
    
    // Zamknij wszystkie inne dropdowny
    allDropdowns.forEach(d => {
        if (d.id !== id) {
            d.classList.add('hidden');
        }
    });
    
    // Toggle bieżący dropdown
    dropdown.classList.toggle('hidden');
}

// Zamknij dropdowny po kliknięciu poza nimi
document.addEventListener('click', function(event) {
    if (!event.target.closest('[onclick*=\"toggleDropdown\"]')) {
        const allDropdowns = document.querySelectorAll('[id^=\"dropdown-\"]');
        allDropdowns.forEach(d => d.classList.add('hidden'));
    }
});
</script>
{% endblock %}
", "flats/index.twig", "/Users/andrzej.doroszenko/Sites/flats/templates/flats/index.twig");
    }
}
