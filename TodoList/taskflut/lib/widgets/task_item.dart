import 'package:flutter/material.dart';
import 'package:taskflut/models/task.dart';

class TaskItem extends StatefulWidget {
  final Task task;
  final VoidCallback onTap;
  final VoidCallback onDelete;
  final ValueChanged<bool> onToggle;

  const TaskItem({
    super.key,
    required this.task,
    required this.onTap,
    required this.onDelete,
    required this.onToggle,
  });

  @override
  State<TaskItem> createState() => _TaskItemState();
}

class _TaskItemState extends State<TaskItem> {
  late bool _isCompleted;

  @override
  void initState() {
    super.initState();
    _isCompleted = widget.task.isCompleted;
  }

  @override
  void didUpdateWidget(covariant TaskItem oldWidget) {
    super.didUpdateWidget(oldWidget);
    if (oldWidget.task.isCompleted != widget.task.isCompleted) {
      _isCompleted = widget.task.isCompleted;
    }
  }

  @override
  Widget build(BuildContext context) {
    return Card(
      elevation: 2,
      margin: const EdgeInsets.symmetric(vertical: 8),
      shape: RoundedRectangleBorder(
        borderRadius: BorderRadius.circular(10),
      ),
      child: InkWell(
        borderRadius: BorderRadius.circular(10),
        onTap: widget.onTap,
        child: Padding(
          padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 8),
          child: Row(
            children: [
              // Checkbox with smooth toggle animation
              StatefulBuilder(
                builder: (context, setState) {
                  return Checkbox(
                    value: _isCompleted,
                    onChanged: (value) {
                      if (value != null) {
                        setState(() => _isCompleted = value);
                        widget.onToggle(value);
                      }
                    },
                    shape: RoundedRectangleBorder(
                      borderRadius: BorderRadius.circular(4),
                    ),
                    side: MaterialStateBorderSide.resolveWith(
                      (states) => BorderSide(
                        width: 1.5,
                        color: _isCompleted ? Colors.green : Colors.grey,
                      ),
                    ),
                    activeColor: Colors.green,
                  );
                },
              ),
              const SizedBox(width: 8),
              // Task details
              Expanded(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    // Title with completion styling
                    Text(
                      widget.task.title,
                      style: TextStyle(
                        fontSize: 16,
                        fontWeight: FontWeight.w500,
                        decoration: _isCompleted
                            ? TextDecoration.lineThrough
                            : null,
                        color: _isCompleted
                            ? Colors.grey
                            : Theme.of(context).textTheme.titleMedium?.color,
                      ),
                    ),
                    // Description if available
                    if (widget.task.description?.isNotEmpty ?? false) ...[
                      const SizedBox(height: 4),
                      Text(
                        widget.task.description!,
                        style: TextStyle(
                          fontSize: 14,
                          color: Colors.grey[600],
                          decoration: _isCompleted
                              ? TextDecoration.lineThrough
                              : null,
                        ),
                        maxLines: 2,
                        overflow: TextOverflow.ellipsis,
                      ),
                    ],
                    // Due date if available
                    if (widget.task.dueDate != null) ...[
                      const SizedBox(height: 4),
                      Row(
                        children: [
                          Icon(
                            Icons.calendar_today_outlined,
                            size: 14,
                            color: Colors.grey[600],
                          ),
                          const SizedBox(width: 4),
                          Text(
                            widget.task.dueDate!,
                            style: TextStyle(
                              fontSize: 12,
                              color: Colors.grey[600],
                            ),
                          ),
                        ],
                      ),
                    ],
                  ],
                ),
              ),
              // Delete button with confirmation
              IconButton(
                icon: Icon(
                  Icons.delete_outline,
                  color: Colors.red[400],
                ),
                onPressed: () => _showDeleteConfirmation(context),
              ),
            ],
          ),
        ),
      ),
    );
  }

  Future<void> _showDeleteConfirmation(BuildContext context) async {
    final confirmed = await showDialog<bool>(
      context: context,
      builder: (context) => AlertDialog(
        title: const Text('Delete Task'),
        content: const Text('Are you sure you want to delete this task?'),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(context, false),
            child: const Text('Cancel'),
          ),
          TextButton(
            onPressed: () => Navigator.pop(context, true),
            child: const Text(
              'Delete',
              style: TextStyle(color: Colors.red),
            ),
          ),
        ],
      ),
    );

    if (confirmed == true) {
      widget.onDelete();
    }
  }
}