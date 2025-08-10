import 'package:flutter/material.dart';
import 'package:taskflut/models/task.dart';
import 'package:taskflut/widgets/custom_app_bar.dart';
import 'package:taskflut/widgets/custom_button.dart';
import 'package:taskflut/widgets/custom_text_field.dart';

class TaskDetailScreen extends StatefulWidget {
  final Task task;

  const TaskDetailScreen({super.key, required this.task});

  @override
  State<TaskDetailScreen> createState() => _TaskDetailScreenState();
}

class _TaskDetailScreenState extends State<TaskDetailScreen> {
  late final _titleController = TextEditingController(text: widget.task.title);
  late final _descriptionController =
      TextEditingController(text: widget.task.description ?? '');

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: const CustomAppBar(title: 'Task Details'),
      body: Padding(
        padding: const EdgeInsets.all(20),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(
              'Title',
              style: TextStyle(
                fontSize: 16,
                color: Colors.grey[700],
              ),
            ),
            const SizedBox(height: 5),
            Text(
              widget.task.title,
              style: const TextStyle(
                fontSize: 18,
                fontWeight: FontWeight.bold,
              ),
            ),
            const SizedBox(height: 20),
            if (widget.task.description != null) ...[
              Text(
                'Description',
                style: TextStyle(
                  fontSize: 16,
                  color: Colors.grey[700],
                ),
              ),
              const SizedBox(height: 5),
              Text(
                widget.task.description!,
                style: const TextStyle(fontSize: 16),
              ),
              const SizedBox(height: 20),
            ],
            Row(
              children: [
                Text(
                  'Status: ',
                  style: TextStyle(
                    fontSize: 16,
                    color: Colors.grey[700],
                  ),
                ),
                Text(
                  widget.task.isCompleted ? 'Completed' : 'Pending',
                  style: TextStyle(
                    fontSize: 16,
                    color: widget.task.isCompleted ? Colors.green : Colors.red,
                    fontWeight: FontWeight.bold,
                  ),
                ),
              ],
            ),
            const SizedBox(height: 30),
            CustomButton(
              text: 'Edit Task',
              onPressed: () => _showEditDialog(context),
            ),
          ],
        ),
      ),
    );
  }

  void _showEditDialog(BuildContext context) {
    showDialog(
      context: context,
      builder: (context) {
        return AlertDialog(
          title: const Text('Edit Task'),
          content: Column(
            mainAxisSize: MainAxisSize.min,
            children: [
              CustomTextField(
                label: 'Title',
                controller: _titleController,
              ),
              const SizedBox(height: 20),
              CustomTextField(
                label: 'Description',
                controller: _descriptionController,
                maxLines: 3,
              ),
            ],
          ),
          actions: [
            TextButton(
              onPressed: () => Navigator.pop(context),
              child: const Text('Cancel'),
            ),
            TextButton(
              onPressed: () {
                final updatedTask = Task(
                  id: widget.task.id,
                  title: _titleController.text,
                  description: _descriptionController.text.isEmpty
                      ? null
                      : _descriptionController.text,
                  dueDate: widget.task.dueDate,
                  priority: widget.task.priority,
                  isCompleted: widget.task.isCompleted,
                  createdAt: widget.task.createdAt,
                  updatedAt: DateTime.now(),
                );
                Navigator.pop(context);
                Navigator.pop(context, updatedTask);
              },
              child: const Text('Save'),
            ),
          ],
        );
      },
    );
  }

  @override
  void dispose() {
    _titleController.dispose();
    _descriptionController.dispose();
    super.dispose();
  }
}