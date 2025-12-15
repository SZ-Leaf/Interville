import { useAuth } from "@/hooks/useAuth";
import { useState } from "react";
import { Link, useNavigate } from "react-router";
import { AlertCircle, Trophy, Eye, EyeOff } from "lucide-react";
import * as authService from "@/service/authService";

function SignupPage() {
  const { login } = useAuth();
  const navigate = useNavigate();
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [username, setUsername] = useState("");
  const [confirmPassword, setConfirmPassword] = useState("");
  const [isLoading, setIsLoading] = useState(false);
  const [error, setError] = useState("");
  const [showPassword, setShowPassword] = useState(false);
  const [showConfirmPassword, setShowConfirmPassword] = useState(false);

  const handleSubmit = async (e) => {
    e.preventDefault();
    setError("");

    // Validate password match
    if (password !== confirmPassword) {
      setError("Les mots de passe ne correspondent pas");
      return;
    }

    // Validate password length
    if (password.length < 6) {
      setError("Le mot de passe doit contenir au moins 6 caractères");
      return;
    }

    setIsLoading(true);

    try {
      // Call the registration service
      const response = await authService.register({
        email,
        password,
        username,
      });

      // Extract token from response
      const token = response.token;

      if (token) {
        // Update auth context with the token
        login(token);
        // Redirect to challenges page
        navigate("/challenges");
      } else {
        setError("No token received from server");
      }
    } catch (err) {
      setError(err.message || "Registration failed. Please try again.");
      console.error("Registration error:", err);
    } finally {
      setIsLoading(false);
    }
  };

  return (
    <div className="min-h-screen flex items-center justify-center bg-gray-100 px-4 py-12">
      <div className="max-w-md w-full">
        <div className="bg-white rounded-3xl shadow-lg p-10">
          <div className="text-center mb-8">
            <div className="flex justify-center mb-6">
              <div className="w-14 h-14 bg-gray-900 rounded-xl flex items-center justify-center">
                <Trophy className="w-7 h-7 text-white" />
              </div>
            </div>
            <h1 className="text-2xl font-semibold text-gray-900 mb-2">
              Créer un compte
            </h1>
            <p className="text-sm text-gray-500">
              Rejoignez ChallengHub et commencez votre aventure
            </p>
          </div>
          {error && (
            <div className="mb-6 p-3 bg-red-50 border border-red-200 rounded-xl flex items-start gap-3">
              <AlertCircle className="w-4 h-4 text-red-600 flex-shrink-0 mt-0.5" />
              <p className="text-xs text-red-800">{error}</p>
            </div>
          )}

          <form onSubmit={handleSubmit} className="space-y-4">
            <div>
              <input
                type="text"
                value={username}
                onChange={(e) => setUsername(e.target.value)}
                placeholder="Nom complet"
                className="w-full px-4 py-3 border-b-2 border-gray-200 focus:outline-none focus:border-blue-500 transition-colors text-sm placeholder:text-gray-400"
                required
                disabled={isLoading}
              />
            </div>

            <div>
              <input
                type="email"
                value={email}
                onChange={(e) => setEmail(e.target.value)}
                placeholder="Email"
                className="w-full px-4 py-3 border-b-2 border-gray-200 focus:outline-none focus:border-blue-500 transition-colors text-sm placeholder:text-gray-400"
                required
                disabled={isLoading}
              />
            </div>

            <div className="relative">
              <input
                type={showPassword ? "text" : "password"}
                value={password}
                onChange={(e) => setPassword(e.target.value)}
                placeholder="Mot de passe"
                className="w-full px-4 py-3 pr-10 border-b-2 border-gray-200 focus:outline-none focus:border-blue-500 transition-colors text-sm placeholder:text-gray-400"
                required
                disabled={isLoading}
                minLength={6}
              />
              <button
                type="button"
                onClick={() => setShowPassword(!showPassword)}
                className="absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 cursor-pointer"
                disabled={isLoading}
              >
                {showPassword ? (
                  <EyeOff className="w-4 h-4" />
                ) : (
                  <Eye className="w-4 h-4" />
                )}
              </button>
            </div>

            <div className="relative">
              <input
                type={showConfirmPassword ? "text" : "password"}
                value={confirmPassword}
                onChange={(e) => setConfirmPassword(e.target.value)}
                placeholder="Confirmer le mot de passe"
                className="w-full px-4 py-3 pr-10 border-b-2 border-gray-200 focus:outline-none focus:border-blue-500 transition-colors text-sm placeholder:text-gray-400"
                required
                disabled={isLoading}
                minLength={6}
              />
              <button
                type="button"
                onClick={() => setShowConfirmPassword(!showConfirmPassword)}
                className="absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 cursor-pointer"
                disabled={isLoading}
              >
                {showConfirmPassword ? (
                  <EyeOff className="w-4 h-4" />
                ) : (
                  <Eye className="w-4 h-4" />
                )}
              </button>
            </div>

            <button
              type="submit"
              className="w-full bg-gradient-to-r from-blue-500 to-blue-600 text-white py-3 rounded-full hover:shadow-lg hover:scale-[1.02] transition-all disabled:opacity-50 disabled:cursor-not-allowed text-sm font-medium uppercase tracking-wide mt-6"
              disabled={isLoading}
            >
              {isLoading ? "Création..." : "Créer un compte"}
            </button>
          </form>

          <div className="mt-8 text-center">
            <p className="text-sm text-gray-500">
              Vous avez déjà un compte ?{" "}
              <Link
                to="/login"
                className="text-blue-600 hover:text-blue-700 font-medium"
              >
                Se connecter
              </Link>
            </p>
          </div>
        </div>
      </div>
    </div>
  );
}

export default SignupPage;
