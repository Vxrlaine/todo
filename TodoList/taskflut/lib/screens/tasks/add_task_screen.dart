import 'package:flutter/material.dart';
import 'package:taskflut/models/task.dart';

/// Custom Button
class CustomButton extends StatelessWidget {
  final String text;
  final VoidCallback onPressed;
  final bool isLoading;
  const CustomButton({
    Key? key,
    required this.text,
    required this.onPressed,
    this.isLoading = false,
  }) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return SizedBox(
      width: double.infinity,
      height: 50,
      child: ElevatedButton(
        style: ElevatedButton.styleFrom(
          backgroundColor: const Color(0xffff8fab),
          shape: RoundedRectangleBorder(
            borderRadius: BorderRadius.circular(12),
          ),
        ),
        onPressed: isLoading ? null : onPressed,
        child: isLoading
            ? const CircularProgressIndicator(color: Colors.white)
            : Text(
                text,
                style: const TextStyle(fontSize: 16, color: Colors.white),
              ),
      ),
    );
  }
}

/// Custom Text Field
class CustomTextField extends StatelessWidget {
  final TextEditingController controller;
  final String label;
  final String? Function(String?)? validator;
  final int maxLines;
  const CustomTextField({
    Key? key,
    required this.controller,
    required this.label,
    this.validator,
    this.maxLines = 1,
  }) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return TextFormField(
      controller: controller,
      validator: validator,
      maxLines: maxLines,
      decoration: InputDecoration(
        labelText: label,
        border: OutlineInputBorder(
          borderRadius: BorderRadius.circular(12),
        ),
        focusedBorder: OutlineInputBorder(
          borderRadius: BorderRadius.circular(12),
          borderSide: const BorderSide(color: Color(0xffff8fab), width: 2),
        ),
      ),
    );
  }
}

/// Custom AppBar
class CustomAppBar extends StatelessWidget implements PreferredSizeWidget {
  final String title;
  final List<Widget>? actions;
  final Color backgroundColor;
  final double elevation;
  final ShapeBorder? shape;

  const CustomAppBar({
    Key? key,
    required this.title,
    this.actions,
    this.backgroundColor = const Color(0xffff8fab),
    this.elevation = 4,
    this.shape,
  }) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return AppBar(
      backgroundColor: backgroundColor,
      elevation: elevation,
      shape: shape,
      title: Text(
        title,
        style: const TextStyle(fontWeight: FontWeight.bold),
      ),
      actions: actions,
    );
  }

  @override
  Size get preferredSize => const Size.fromHeight(kToolbarHeight);
}

/// Add Task Screen
class AddTaskScreen extends StatefulWidget {
  const AddTaskScreen({Key? key}) : super(key: key);

  @override
  State<AddTaskScreen> createState() => _AddTaskScreenState();
}

class _AddTaskScreenState extends State<AddTaskScreen> {
  final _formKey = GlobalKey<FormState>();
  final _titleController = TextEditingController();
  final _descriptionController = TextEditingController();
  DateTime? _dueDate;
  int? _priority;
  bool _isLoading = false;

  Future<void> _selectDate(BuildContext context) async {
    final picked = await showDatePicker(
      context: context,
      initialDate: DateTime.now(),
      firstDate: DateTime.now(),
      lastDate: DateTime(2100),
    );
    if (picked != null && picked != _dueDate) {
      setState(() {
        _dueDate = picked;
      });
    }
  }

  void _showPriorityDialog() {
    showDialog(
      context: context,
      builder: (context) => AlertDialog(
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
        title: const Text('Select Priority'),
        content: Column(
          mainAxisSize: MainAxisSize.min,
          children: List.generate(5, (index) {
            final priority = index + 1;
            return ListTile(
              title: Text('Priority $priority'),
              trailing: _priority == priority
                  ? const Icon(Icons.check, color: Color(0xffff8fab))
                  : null,
              onTap: () {
                setState(() => _priority = priority);
                Navigator.pop(context);
              },
            );
          }),
        ),
      ),
    );
  }

  Future<void> _submitTask() async {
    if (!_formKey.currentState!.validate()) return;

    setState(() => _isLoading = true);

    try {
      final newTask = Task(
        id: 0,
        title: _titleController.text.trim(),
        description: _descriptionController.text.trim().isEmpty
            ? null
            : _descriptionController.text.trim(),
        dueDate: _dueDate?.toIso8601String(),
        priority: _priority,
        isCompleted: false,
        createdAt: DateTime.now(),
        updatedAt: DateTime.now(),
      );

      // await ApiService.createTask(newTask);

      if (!mounted) return;
      Navigator.pop(context, newTask);
    } catch (e) {
      if (!mounted) return;
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text('Failed to create task: $e'),
          backgroundColor: Colors.red,
        ),
      );
    } finally {
      if (mounted) setState(() => _isLoading = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: const CustomAppBar(
        title: 'Add New Task',
        shape: RoundedRectangleBorder(
          borderRadius: BorderRadius.vertical(bottom: Radius.circular(16)),
        ),
      ),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(16.0),
        child: Form(
          key: _formKey,
          child: Column(
            children: [
              CustomTextField(
                controller: _titleController,
                label: 'Task Title *',
                validator: (value) {
                  if (value == null || value.trim().isEmpty) {
                    return 'Title is required';
                  }
                  return null;
                },
              ),
              const SizedBox(height: 20),
              CustomTextField(
                controller: _descriptionController,
                label: 'Description',
                maxLines: 3,
              ),
              const SizedBox(height: 20),
              InkWell(
                onTap: () => _selectDate(context),
                child: InputDecorator(
                  decoration: InputDecoration(
                    labelText: 'Due Date',
                    border: OutlineInputBorder(
                      borderRadius: BorderRadius.circular(12),
                    ),
                  ),
                  child: Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      Text(
                        _dueDate == null
                            ? 'Select a date'
                            : '${_dueDate!.day}/${_dueDate!.month}/${_dueDate!.year}',
                      ),
                      const Icon(Icons.calendar_today, color: Color(0xffff8fab)),
                    ],
                  ),
                ),
              ),
              const SizedBox(height: 20),
              InkWell(
                onTap: _showPriorityDialog,
                child: InputDecorator(
                  decoration: InputDecoration(
                    labelText: 'Priority',
                    border: OutlineInputBorder(
                      borderRadius: BorderRadius.circular(12),
                    ),
                  ),
                  child: Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      Text(
                        _priority == null
                            ? 'Select priority'
                            : 'Priority $_priority',
                      ),
                      const Icon(Icons.arrow_drop_down, color: Color(0xffff8fab)),
                    ],
                  ),
                ),
              ),
              const SizedBox(height: 30),
              CustomButton(
                text: 'Create Task',
                onPressed: _submitTask,
                isLoading: _isLoading,
              ),
            ],
          ),
        ),
      ),
    );
  }

  @override
  void dispose() {
    _titleController.dispose();
    _descriptionController.dispose();
    super.dispose();
  }
}
