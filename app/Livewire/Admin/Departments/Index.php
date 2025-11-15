<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Departments;

use App\Contracts\Services\DepartmentServiceInterface;
use App\DTOs\CreateDepartmentDTO;
use App\DTOs\UpdateDepartmentDTO;
use Livewire\Component;

class Index extends Component
{
    public string $search = '';

    public ?int $editingId = null;

    public string $name = '';

    public string $code = '';

    public string $description = '';

    public bool $is_active = true;

    public bool $showModal = false;

    public function create(): void
    {
        $this->reset(['editingId', 'name', 'code', 'description', 'is_active']);
        $this->is_active = true;
        $this->showModal = true;
    }

    public function edit(int $id, DepartmentServiceInterface $service): void
    {
        $dept = $service->getDepartmentById($id);
        if ($dept) {
            $this->editingId = $id;
            $this->name = $dept->name;
            $this->code = $dept->code;
            $this->description = $dept->description ?? '';
            $this->is_active = $dept->isActive;
            $this->showModal = true;
        }
    }

    public function save(DepartmentServiceInterface $service): void
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:20',
            'description' => 'nullable|string|max:1000',
        ]);

        if ($this->editingId) {
            $dto = new UpdateDepartmentDTO($this->name, strtoupper($this->code), $this->description, $this->is_active);
            $service->updateDepartment($this->editingId, $dto);
            session()->flash('success', 'Department updated successfully.');
        } else {
            $dto = new CreateDepartmentDTO($this->name, strtoupper($this->code), $this->description, $this->is_active);
            $service->createDepartment($dto);
            session()->flash('success', 'Department created successfully.');
        }

        $this->showModal = false;
        $this->reset(['editingId', 'name', 'code', 'description']);
    }

    public function delete(int $id, DepartmentServiceInterface $service): void
    {
        if ($service->canDelete($id)) {
            $service->deleteDepartment($id);
            session()->flash('success', 'Department deleted successfully.');
        } else {
            session()->flash('error', 'Cannot delete department with existing positions or employees.');
        }
    }

    public function render(DepartmentServiceInterface $service)
    {
        $departments = collect($service->getAllDepartments());

        if ($this->search) {
            $departments = $departments->filter(fn ($d) => str_contains(strtolower($d->name), strtolower($this->search))
                || str_contains(strtolower($d->code), strtolower($this->search)));
        }

        return view('livewire.admin.departments.index', [
            'departments' => $departments,
        ])->layout('layouts.app', ['title' => 'Departments']);
    }
}
