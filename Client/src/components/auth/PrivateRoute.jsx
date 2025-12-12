import { Navigate, Outlet } from "react-router";
import { useAuth } from "@/hooks/useAuth";

/**
 * PrivateRoute Component
 *
 * Protects routes that require authentication.
 * Optionally checks for specific roles.
 *
 * @param {string[]} roles - Array of required roles (e.g., ['ROLE_ADMIN']).
 */
const PrivateRoute = ({ roles = [] }) => {
  const { user } = useAuth();

  // If not logged in, redirect to login page
  if (!user) {
    return <Navigate to="/login" replace />;
  }

  // If roles are specified, check if user has at least one of them
  if (roles.length > 0) {
    const hasRole = roles.some((role) => user.roles?.includes(role));
    if (!hasRole) {
      return <Navigate to="/" replace />; // Redirect to home/dashboard if unauthorized
    }
  }

  return <Outlet />;
};

export default PrivateRoute;
