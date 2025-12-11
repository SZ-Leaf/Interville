import { useContext } from "react";
import { AuthContext } from "@/contexts/context";

/**
 * Custom hook to access the authentication context.
 *
 * @throws {Error} If used outside of an AuthProvider.
 * @returns {Object} The auth context value (user, login, logout, isAdmin).
 */
export const useAuth = () => {
  const context = useContext(AuthContext);
  if (!context) {
    throw new Error("useAuth must be used within an AuthProvider");
  }
  return context;
};
