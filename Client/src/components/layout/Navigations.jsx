import { Link, useLocation, useNavigate } from "react-router";
import {
  Trophy,
  MessageCircle,
  User,
  LogOut,
  Shield,
  PlusCircle,
} from "lucide-react";
import { useAuth } from "@/hooks/useAuth";

function Navigation() {
  const location = useLocation();
  const navigate = useNavigate();
  const { user, logout, isAdmin } = useAuth();

  const isActive = (path) => location.pathname === path;

  const handleLogout = () => {
    logout();
    navigate("/login");
  };

  // Don't show navigation on login/signup pages
  if (!user) {
    return null;
  }

  return (
    <nav className="shadow-sm border-b">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="flex justify-between h-16">
          <div className="flex items-center gap-8">
            <Link to="/challenges" className="flex items-center gap-2">
              <Trophy className="w-8 h-8 text-primary" />
              <span className="text-xl">ChallengHub</span>
            </Link>

            <div className="flex gap-1">
              <Link
                to="/challenges"
                className={`px-4 py-2 rounded-lg  ${
                  isActive("/challenges")
                    ? "bg-secondary text-gray-100"
                    : "bg-transparent text-gray-700 hover:bg-gray-100"
                }`}
              >
                Défis
              </Link>
              <Link
                to="/chat"
                className={`px-4 py-2 rounded-lg  flex items-center gap-2 ${
                  isActive("/chat")
                    ? "bg-secondary text-primary"
                    : "bg-transparent text-gray-700 hover:bg-gray-100"
                }`}
              >
                <MessageCircle className="w-4 h-4" />
                Discussion
              </Link>
              {isAdmin() && (
                <Link
                  to="/admin"
                  className={`px-4 py-2 rounded-lg  flex items-center gap-2 ${
                    isActive("/admin")
                      ? "bg-secondary text-primary"
                      : "bg-transparent text-gray-700 hover:bg-gray-100"
                  }`}
                >
                  <Shield className="w-4 h-4" />
                  Admin
                </Link>
              )}
            </div>
          </div>

          <div className="flex items-center gap-3">
            <Link
              to="/create-challenge"
              className="px-4 py-2 bg-primary rounded-lg hover:opacity-90 transition-opacity flex items-center gap-2 text-white"
            >
              <PlusCircle className="w-4 h-4" />
              Créer un défi
            </Link>

            <Link
              to="/profile"
              className="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-gray-100"
            >
              <img
                src={
                  user.avatar ||
                  `https://api.dicebear.com/7.x/avataaars/svg?seed=${user.email}`
                }
                alt={user.username || user.name}
                className="w-8 h-8 rounded-full"
              />
              <span>{user.username || user.name}</span>
            </Link>

            <button
              onClick={handleLogout}
              className="p-2 hover:bg-gray-100 rounded-lg"
              title="Déconnexion"
            >
              <LogOut className="w-5 h-5" />
            </button>
          </div>
        </div>
      </div>
    </nav>
  );
}

export default Navigation;
