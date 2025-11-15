<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Positions;

use App\Contracts\Services\DepartmentServiceInterface;
use App\Contracts\Services\PositionServiceInterface;
use App\DTOs\CreatePositionDTO;
use App\DTOs\UpdatePositionDTO;
use Livewire\Component;

class Index extends Component
{
    public string $search = '';

    public ?int $editingId = null;

    public string $name = '';

    public string $code = '';

    public string $description = '';

    public int $level = 1;

    public ?int $department_id = null;

    public bool $is_active = true;

    public bool $showModal = false;

    public function create(): void
    {
        $this->reset(['editingId', 'name', 'code', 'description', 'level', 'department_id', 'is_active']);
        $this->level = 1;
        $this->is_active = true;
        $this->showModal = true;
    }

    public function edit(int $id, PositionServiceInterface $service): void
    {
        $pos = $service->getPositionById($id);
        if ($pos) {
            $this->editingId = $id;
            $this->name = $pos->name;
            $this->code = $pos->code;
            $this->description = $pos->description ?? '';
            $this->level = $pos->level;
            $this->department_id = $pos->departmentId;
            $this->is_active = $pos->isActive;
            $this->showModal = true;
        }
    }

    public function save(PositionServiceInterface $service): void
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:20',
            'level' => 'required|integer|min:1|max:5',
        ]);

        if ($this->editingId) {
            $dto = new UpdatePositionDTO($this->name, strtoupper($this->code), $this->description, $this->level, $this->department_id, $this->is_active);
            $service->updatePosition($this->editingId, $dto);
            session()->flash('success', 'Position updated successfully.');
        } else {
            $dto = new CreatePositionDTO($this->name, strtoupper($this->code), $this->description, $this->level, $this->department_id, $this->is_active);
            $service->createPosition($dto);
            session()->flash('success', 'Position created successfully.');
        }

        $this->showModal = false;
        $this->reset(['editingId', 'name', 'code', 'description', 'level', 'department_id']);
    }

    public function delete(int $id, PositionServiceInterface $service): void
    {
        if ($service->canDelete($id)) {
            $service->deletePosition($id);
            session()->flash('success', 'Position deleted successfully.');
        } else {
            session()->flash('error', 'Cannot delete position with existing employees.');
        }
    }

    public function render(PositionServiceInterface $service, DepartmentServiceInterface $deptService)
    {
        $positions = collect($service->getAllPositions());
        $departments = collect($deptService->getActiveDepartments());

        if ($this->search) {
            $positions = $positions->filter(fn ($p) => str_contains(strtolower($p->name), strtolower($this->search))
                || str_contains(strtolower($p->code), strtolower($this->search)));
        }

        return view('livewire.admin.positions.index', [
            'positions' => $positions->sortByDesc('level'),
            'departments' => $departments,
        ])->layout('layouts.app', ['title' => 'Positions']);
    }
}
