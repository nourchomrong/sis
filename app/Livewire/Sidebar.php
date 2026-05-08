<?php

namespace App\Livewire;

use Livewire\Component;

class Sidebar extends Component
{
    public $menus = [];

    /**
     * Controls whether the mobile sidebar stays open across Livewire updates.
     */
    public $sidebarOpen = false;

    protected $listeners = [
        'toggleSidebar' => 'toggleSidebar',
        'closeSidebar' => 'closeSidebar',
    ];

    // ✅ Menu items with route & icon
    public $menuItems = [
        [
            'title' => 'Dashboard',
            'route' => 'admin.dashboard',
            'icon' => 'svg/dashboard.svg',
        ],
        [
            'title' => 'Access Control',
            'key' => 'accessMenu',
            'icon' => 'svg/user-management.svg',
            'children' => [
                [
                    'title' => 'Users Accounts',
                    'route' => 'admin.users',
                    'icon' => 'svg/user.svg',
                ],
                [
                    'title' => 'Activity Logs',
                    'route' => 'admin.activitylogs',
                    'icon' => 'svg/audit.svg',
                ],
            ]
        ],
        [
            'title' => 'Members',
            'key' => 'membersMenu',
            'icon' => 'svg/academic.svg',
            'children' => [
                [
                    'title' => 'Students Records',
                    'route' => 'admin.students',
                    'icon' => 'svg/student.svg',
                ],
                [
                    'title' => 'Teachers Records',
                    'route' => 'admin.teachers',
                    'icon' => 'svg/teacher.svg',
                ],
            ]
        ],
        [
            'title' => 'Academics',
            'key' => 'academicMenu',
            'icon' => 'svg/classroom.svg',
            'children' => [
                [
                    'title' => 'Classrooms',
                    'route' => 'admin.classrooms',
                    'icon' => 'svg/classroom.svg',
                ],
                [
                    'title' => 'Classes',
                    'route' => 'admin.classes',
                    'icon' => 'svg/class.svg',
                ],
                                [
                    'title' => 'Schedules',
                    'route' => 'admin.schedules',
                    'icon' => 'svg/schedule.svg',
                ],
            ]
        ],
         [
            'title' => 'Academic Settings',
            'key' => 'academicSettingsMenu',
            'icon' => 'svg/year.svg',
            'children' => [
                [
                    'title' => 'AcademicYears',
                    'route' => 'admin.academicyears',
                    'icon' => 'svg/year.svg',
                ],
                [
                    'title' => 'Subjects',
                    'route' => 'admin.subjects',
                    'icon' => 'svg/subject.svg',
                ],
                [
                    'title' => 'Terms',
                    'route' => 'admin.terms',
                    'icon' => 'svg/term.svg',
                ],
            ]
        ],
        
    ];

    // Load SVG files on component mount and restore UI state from session
    public function mount()
    {
        $this->sidebarOpen = session('sidebarOpen', false);
        $this->menus = session('sidebarMenus', []);

        $this->loadSvgIcons($this->menuItems);
    }

    // Recursively load SVG content from files
    private function loadSvgIcons(&$items)
    {
        foreach ($items as &$item) {
            if (isset($item['icon']) && !str_contains($item['icon'], '<svg')) {
                $svgPath = public_path($item['icon']);
                if (file_exists($svgPath)) {
                    $svgContent = file_get_contents($svgPath);
                    // Add Tailwind classes for proper sizing
                    $class = isset($item['children']) ? 'w-4 h-4 mr-1' : 'w-5 h-5 mr-2';
                    $svgContent = str_replace('<svg', '<svg class="' . $class . '"', $svgContent);
                    $item['icon'] = $svgContent;
                }
            }
            if (isset($item['children'])) {
                $this->loadSvgIcons($item['children']);
            }
        }
    }

    // Toggle dropdown menu
    public function toggleMenu($menu)
    {
        $this->menus[$menu] = !($this->menus[$menu] ?? false);
        session(['sidebarMenus' => $this->menus]);
    }

    // Toggle mobile sidebar
    public function toggleSidebar()
    {
        $this->sidebarOpen = ! $this->sidebarOpen;
        session(['sidebarOpen' => $this->sidebarOpen]);
    }

    // Close mobile sidebar
    public function closeSidebar()
    {
        $this->sidebarOpen = false;
        session(['sidebarOpen' => false]);
    }

    // Check if dropdown is expanded
    public function isExpanded($menu)
    {
        return $this->menus[$menu] ?? false;
    }

    public function render()
    {
        return view('livewire.sidebar');
    }
}