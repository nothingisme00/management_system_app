<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Employee extends Model
{
    /** @use HasFactory<\Database\Factories\EmployeeFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'employee_id',
        'department_id',
        'position_id',
        'phone_number',
        'address',
        'join_date',
        'termination_date',
        'employment_status',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'join_date' => 'date',
            'termination_date' => 'date',
        ];
    }

    /**
     * Get the user that owns the employee record.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the department that the employee belongs to.
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Get the position of the employee.
     */
    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class);
    }

    /**
     * Scope query to only include active employees.
     */
    public function scopeActive($query)
    {
        return $query->where('employment_status', 'active');
    }

    /**
     * Scope query to filter by department.
     */
    public function scopeInDepartment($query, int $departmentId)
    {
        return $query->where('department_id', $departmentId);
    }

    /**
     * Scope query to filter by position.
     */
    public function scopeWithPosition($query, int $positionId)
    {
        return $query->where('position_id', $positionId);
    }

    /**
     * Check if employee is currently active.
     */
    public function isActive(): bool
    {
        return $this->employment_status === 'active';
    }

    /**
     * Check if employee is terminated.
     */
    public function isTerminated(): bool
    {
        return $this->employment_status === 'terminated';
    }
}
