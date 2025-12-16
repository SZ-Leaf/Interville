import api from "./api";

/**
 * Challenge Service
 * Handles all challenge-related API calls
 */

/**
 * Get all challenges with optional filters
 * @param {Object} filters - Filter parameters
 * @param {string} filters.category - Filter by category
 * @param {string} filters.city - Filter by city
 * @param {string} filters.status - Filter by status (active, upcoming, completed)
 * @returns {Promise<Array>} Array of challenges
 */
export const getChallenges = async (filters = {}) => {
  try {
    const response = await api.get("/challenges", { params: filters });
    return response.data;
  } catch (error) {
    throw error.response?.data || { message: "Failed to fetch challenges" };
  }
};

/**
 * Get a single challenge by ID
 * @param {string|number} id - Challenge ID
 * @returns {Promise<Object>} Challenge data
 */
export const getChallengeById = async (id) => {
  try {
    const response = await api.get(`/challenges/${id}`);
    return response.data;
  } catch (error) {
    throw error.response?.data || { message: "Failed to fetch challenge" };
  }
};

/**
 * Create a new challenge
 * @param {Object} challengeData - Challenge data
 * @returns {Promise<Object>} Created challenge
 */
export const createChallenge = async (challengeData) => {
  try {
    const response = await api.post("/challenges", challengeData);
    return response.data;
  } catch (error) {
    throw error.response?.data || { message: "Failed to create challenge" };
  }
};

/**
 * Update an existing challenge
 * @param {string|number} id - Challenge ID
 * @param {Object} challengeData - Updated challenge data
 * @returns {Promise<Object>} Updated challenge
 */
export const updateChallenge = async (id, challengeData) => {
  try {
    const response = await api.put(`/challenges/${id}`, challengeData);
    return response.data;
  } catch (error) {
    throw error.response?.data || { message: "Failed to update challenge" };
  }
};

/**
 * Delete a challenge
 * @param {string|number} id - Challenge ID
 * @returns {Promise<void>}
 */
export const deleteChallenge = async (id) => {
  try {
    await api.delete(`/challenges/${id}`);
  } catch (error) {
    throw error.response?.data || { message: "Failed to delete challenge" };
  }
};

/**
 * Join a challenge (create participation)
 * @param {string|number} challengeId - Challenge ID
 * @returns {Promise<Object>} Participation data
 */
export const joinChallenge = async (challengeId) => {
  try {
    const response = await api.post(`/challenges/${challengeId}/join`);
    return response.data;
  } catch (error) {
    throw error.response?.data || { message: "Failed to join challenge" };
  }
};

/**
 * Leave a challenge
 * @param {string|number} challengeId - Challenge ID
 * @returns {Promise<void>}
 */
export const leaveChallenge = async (challengeId) => {
  try {
    await api.delete(`/challenges/${challengeId}/leave`);
  } catch (error) {
    throw error.response?.data || { message: "Failed to leave challenge" };
  }
};

/**
 * Get comments for a challenge
 * @param {string|number} challengeId - Challenge ID
 * @returns {Promise<Array>} Array of comments
 */
export const getChallengeComments = async (challengeId) => {
  try {
    const response = await api.get(`/challenges/${challengeId}/comments`);
    return response.data;
  } catch (error) {
    throw error.response?.data || { message: "Failed to fetch comments" };
  }
};

/**
 * Add a comment to a challenge
 * @param {string|number} challengeId - Challenge ID
 * @param {Object} commentData - Comment data
 * @param {string} commentData.content - Comment text
 * @returns {Promise<Object>} Created comment
 */
export const addChallengeComment = async (challengeId, commentData) => {
  try {
    const response = await api.post(
      `/challenges/${challengeId}/comments`,
      commentData
    );
    return response.data;
  } catch (error) {
    throw error.response?.data || { message: "Failed to add comment" };
  }
};

/**
 * Get user's participation in a challenge
 * @param {string|number} challengeId - Challenge ID
 * @returns {Promise<Object>} Participation data
 */
export const getUserParticipation = async (challengeId) => {
  try {
    const response = await api.get(`/challenges/${challengeId}/participation`);
    return response.data;
  } catch (error) {
    throw error.response?.data || { message: "Failed to fetch participation" };
  }
};

/**
 * Update user's progress in a challenge
 * @param {string|number} challengeId - Challenge ID
 * @param {Object} progressData - Progress data
 * @returns {Promise<Object>} Updated participation
 */
export const updateChallengeProgress = async (challengeId, progressData) => {
  try {
    const response = await api.put(
      `/challenges/${challengeId}/progress`,
      progressData
    );
    return response.data;
  } catch (error) {
    throw error.response?.data || { message: "Failed to update progress" };
  }
};

/**
 * Get categories
 * @returns {Promise<Array>} Array of categories
 */
export const getCategories = async () => {
  try {
    const response = await api.get("/categories");
    return response.data;
  } catch (error) {
    throw error.response?.data || { message: "Failed to fetch categories" };
  }
};
