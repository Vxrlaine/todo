import 'dart:convert';
import 'package:http/http.dart' as http;
import 'package:taskflut/models/task.dart';
import 'package:taskflut/utils/constants.dart';
import 'package:taskflut/utils/shared_prefs.dart';

class ApiService {
  static Future<Map<String, String>> _getHeaders() async {
    final token = await SharedPrefs.getString(AppConstants.tokenKey);
    return {
      'Content-Type': 'application/json', // Added content type
      'Accept': 'application/json',
      'Authorization': 'Bearer $token',
    };
  }

  static Future<List<Task>> getTasks() async {
    try {
      final response = await http.get(
        Uri.parse(ApiConstants.tasks),
        headers: await _getHeaders(),
      );

      print('GET Tasks Status: ${response.statusCode}'); // Debug
      print('GET Tasks Response: ${response.body}'); // Debug

      if (response.statusCode == 200) {
        final responseData = json.decode(response.body);
        final List<dynamic> tasksJson = responseData['data'] ?? [];
        return tasksJson.map((json) => Task.fromJson(json)).toList();
      } else {
        final errorData = json.decode(response.body);
        throw errorData['message'] ?? 'Failed to load tasks';
      }
    } catch (e) {
      print('Error in getTasks: $e');
      rethrow;
    }
  }

  static Future<Task> createTask(Task task) async {
    try {
      final body = jsonEncode({
        'title': task.title,
        'description': task.description,
        'due_date': task.dueDate,
        'priority': task.priority,
      });

      print('Creating Task with: $body'); // Debug

      final response = await http.post(
        Uri.parse(ApiConstants.tasks),
        headers: await _getHeaders(),
        body: body,
      );

      print('Create Task Status: ${response.statusCode}');
      print('Create Task Response: ${response.body}');

      if (response.statusCode == 201) {
        final responseData = json.decode(response.body);
        return Task.fromJson(responseData['data']);
      } else {
        final errorData = json.decode(response.body);
        throw errorData['message'] ?? errorData['error'] ?? 'Failed to create task';
      }
    } catch (e) {
      print('Error in createTask: $e');
      rethrow;
    }
  }

  static Future<Task> updateTask(Task task) async {
    try {
      final body = jsonEncode({
        'title': task.title,
        'description': task.description,
        'due_date': task.dueDate,
        'priority': task.priority,
        'completed': task.isCompleted,
      });

      final response = await http.put(
        Uri.parse('${ApiConstants.tasks}/${task.id}'),
        headers: await _getHeaders(),
        body: body,
      );

      print('Update Task Status: ${response.statusCode}');
      print('Update Task Response: ${response.body}');

      if (response.statusCode == 200) {
        final responseData = json.decode(response.body);
        return Task.fromJson(responseData['data']);
      } else {
        final errorData = json.decode(response.body);
        throw errorData['message'] ?? 'Failed to update task';
      }
    } catch (e) {
      print('Error in updateTask: $e');
      rethrow;
    }
  }

  static Future<void> deleteTask(int taskId) async {
    try {
      final response = await http.delete(
        Uri.parse('${ApiConstants.tasks}/$taskId'),
        headers: await _getHeaders(),
      );

      print('Delete Task Status: ${response.statusCode}');
      print('Delete Task Response: ${response.body}');

      if (response.statusCode != 200) {
        final errorData = json.decode(response.body);
        throw errorData['message'] ?? 'Failed to delete task';
      }
    } catch (e) {
      print('Error in deleteTask: $e');
      rethrow;
    }
  }

/* Removed duplicate toggleTaskStatus method to resolve naming conflict */


static Future<Task> toggleTaskStatus(int taskId, bool completed) async {
  final response = await http.patch(
    Uri.parse('${ApiConstants.tasks}/$taskId/toggle'),
    headers: await _getHeaders(),
    body: jsonEncode({'is_completed': completed}), // Pastikan parameter benar
  );

  if (response.statusCode == 200) {
    return Task.fromJson(jsonDecode(response.body)['data']);
  } else {
    throw Exception('Failed to toggle status');
  }
}


}