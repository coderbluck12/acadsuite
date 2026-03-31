<x-mail::message>
# Hello {{ $student->name }},

A new assignment has been posted for your course: **{{ $assignment->course->title }}** on {{ $tenant->name }}.

**Assignment:** {{ $assignment->title }}  
**Due Date:** {{ $assignment->due_date ? $assignment->due_date->format('d M Y, h:i A') : 'No due date' }}

<x-mail::button :url="route('tenant.student.assignments', ['tenant' => $tenant->subdomain])">
View Assignment
</x-mail::button>

Thanks,<br>
{{ $tenant->name }} Portal
</x-mail::message>
