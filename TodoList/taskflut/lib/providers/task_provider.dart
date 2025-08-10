// task_provider.dart
import 'package:flutter/material.dart';
import 'package:taskflut/api/api_service.dart';
import 'package:taskflut/api/auth_service.dart';
import 'package:taskflut/models/task.dart';

class TaskProvider with ChangeNotifier {
  List<Task> _tasks = [];
  final AuthService authService;
  final ApiService apiService;
  TaskProvider({
    required this.authService,
    required this.apiService,
  });
  bool _isLoading = false;

  List<Task> get tasks => _tasks;
  bool get isLoading => _isLoading;

  Future<void> loadTasks() async {
    _isLoading = true;
    notifyListeners();

    try {
      _tasks = await ApiService.getTasks();
    } catch (e) {
      rethrow;
    } finally {
      _isLoading = false;
      notifyListeners();
    }
  }

  Future<void> toggleTaskStatus(int taskId) async {
    final index = _tasks.indexWhere((t) => t.id == taskId);
    if (index == -1) return;

    final task = _tasks[index];
    final newStatus = !task.isCompleted;

    // Optimistic update
    _tasks[index] = task.copyWith(isCompleted: newStatus);
    notifyListeners();

    try {
      final updatedTask = await ApiService.toggleTaskStatus(taskId, newStatus);
      _tasks[index] = updatedTask;
    } catch (e) {
      // Revert on error
      _tasks[index] = task;
      rethrow;
    } finally {
      notifyListeners();
    }
  }

  Future<void> addTask(Task task) async {
    try {
      final newTask = await ApiService.createTask(task);
      _tasks.insert(0, newTask);
      notifyListeners();
    } catch (e) {
      rethrow;
    }
  }

  Future<void> deleteTask(int taskId) async {
    try {
      await ApiService.deleteTask(taskId);
      _tasks.removeWhere((task) => task.id == taskId);
      notifyListeners();
    } catch (e) {
      rethrow;
    }
  }

  Future<void> updateTask(Task result) async {}
}