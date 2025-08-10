class Task {
  final int id;
  final String title;
  final String? description;
  final String? dueDate;
  final int? priority;
  final bool isCompleted;
  final DateTime createdAt;
  final DateTime updatedAt;

  Task({
    required this.id,
    required this.title,
    this.description,
    this.dueDate,
    this.priority,
    required this.isCompleted,
    required this.createdAt,
    required this.updatedAt,
  });

  factory Task.fromJson(Map<String, dynamic> json) {
    try {
      return Task(
        id: json['id'] as int,
        title: json['title'] as String,
        description: json['description'] as String?,
        dueDate: json['due_date'] as String?,
        priority: json['priority'] != null ? int.tryParse(json['priority'].toString()) : null,
        isCompleted: json['is_completed'] as bool? ?? json['completed'] as bool? ?? false,
        createdAt: DateTime.parse(json['created_at'] as String),
        updatedAt: DateTime.parse(json['updated_at'] as String),
      );
    } catch (e) {
      print('Error parsing Task: $e');
      print('JSON received: $json');
      rethrow;
    }
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'title': title,
      'description': description,
      'due_date': dueDate,
      'priority': priority,
      'is_completed': isCompleted,
      'created_at': createdAt.toIso8601String(),
      'updated_at': updatedAt.toIso8601String(),
    };
  }

  Task copyWith({
    int? id,
    String? title,
    String? description,
    String? dueDate,
    int? priority,
    bool? isCompleted,
    DateTime? createdAt,
    DateTime? updatedAt,
  }) {
    return Task(
      id: id ?? this.id,
      title: title ?? this.title,
      description: description ?? this.description,
      dueDate: dueDate ?? this.dueDate,
      priority: priority ?? this.priority,
      isCompleted: isCompleted ?? this.isCompleted,
      createdAt: createdAt ?? this.createdAt,
      updatedAt: updatedAt ?? this.updatedAt,
    );
  }

  @override
  String toString() {
    return 'Task{id: $id, title: $title, isCompleted: $isCompleted}';
  }

  
}

