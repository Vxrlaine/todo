import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:taskflut/api/auth_service.dart';
import 'package:taskflut/screens/auth/register_screen.dart';
import 'package:taskflut/screens/home_screen.dart';
import 'package:taskflut/widgets/custom_button.dart';
import 'package:taskflut/widgets/custom_text_field.dart';

class LoginScreen extends StatefulWidget {
  const LoginScreen({super.key});

  @override
  State<LoginScreen> createState() => _LoginScreenState();
}

class _LoginScreenState extends State<LoginScreen> {
  final _formKey = GlobalKey<FormState>();
  final _emailController = TextEditingController();
  final _passwordController = TextEditingController();
  bool _isLoading = false;

  Future<void> _login() async {
    if (!_formKey.currentState!.validate()) return;

    setState(() => _isLoading = true);
    try {
      final authService = Provider.of<AuthService>(context, listen: false);
      await authService.login(
        _emailController.text.trim(),
        _passwordController.text.trim(),
      );

      if (!mounted) return;
      Navigator.pushReplacement(
        context,
        MaterialPageRoute(builder: (_) => const HomeScreen()),
      );
    } catch (e) {
      String errorMessage = 'An error occurred';
      if (e.toString().contains('Failed to connect')) {
        errorMessage = 'Failed to connect to server. Check your internet connection.';
      } else if (e.toString().contains('Login failed')) {
        errorMessage = 'Invalid email or password';
      }

      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text(errorMessage),
          backgroundColor: Colors.red,
        ),
      );
    } finally {
      setState(() => _isLoading = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: const CustomAppBar(
        backgroundColor: Color(0xffff8fab),
        elevation: 4,
        title: 'Login',
        shape: RoundedRectangleBorder(
          borderRadius: BorderRadius.vertical(bottom: Radius.circular(16)),
        ),
      ),
      body: Padding(
        padding: const EdgeInsets.all(20),
        child: Form(
          key: _formKey,
          child: Column(
            mainAxisAlignment: MainAxisAlignment.center,
            children: [
              CustomTextField(
                label: 'Email',
                controller: _emailController,
                keyboardType: TextInputType.emailAddress,
                validator: (value) {
                  if (value == null || value.isEmpty) {
                    return 'Please enter your email';
                  }
                  if (!value.contains('@')) {
                    return 'Please enter a valid email';
                  }
                  return null;
                },
              ),
              const SizedBox(height: 20),
              CustomTextField(
                label: 'Password',
                controller: _passwordController,
                obscureText: true,
                validator: (value) {
                  if (value == null || value.isEmpty) {
                    return 'Please enter your password';
                  }
                  if (value.length < 6) {
                    return 'Password must be at least 6 characters';
                  }
                  return null;
                },
              ),
              const SizedBox(height: 30),
              CustomButton(
                text: 'Login',
                onPressed: _login,
                isLoading: _isLoading,
              ),
              const SizedBox(height: 20),
              TextButton(
                onPressed: () {
                  Navigator.push(
                    context,
                    MaterialPageRoute(
                      builder: (_) => const RegisterScreen(),
                    ),
                  );
                },
                child: const Text(
                  'Don\'t have an account? Register',
                  style: TextStyle(color: Colors.red),
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }

  @override
  void dispose() {
    _emailController.dispose();
    _passwordController.dispose();
    super.dispose();
  }
}

/// Custom AppBar disatukan di file ini agar tidak bentrok import
class CustomAppBar extends StatelessWidget implements PreferredSizeWidget {
  final Color backgroundColor;
  final double elevation;
  final String title;
  final ShapeBorder shape;
  final List<Widget> actions;

  const CustomAppBar({
    super.key,
    required this.backgroundColor,
    this.elevation = 0,
    required this.title,
    required this.shape,
    this.actions = const [],
  });

  @override
  Widget build(BuildContext context) {
    return AppBar(
      backgroundColor: backgroundColor,
      elevation: elevation,
      title: Text(title),
      shape: shape,
      actions: actions,
    );
  }

  @override
  Size get preferredSize => const Size.fromHeight(kToolbarHeight);
}
